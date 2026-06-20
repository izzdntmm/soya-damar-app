<?php

namespace App\Exports;

use App\Models\Setoran;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SetoranExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithTitle,
    WithEvents
{
    protected $mulai;
    protected $akhir;
    protected $salesId;

    public function __construct(
        string $mulai,
        string $akhir,
        ?int $salesId = null
    ) {
        $this->mulai   = $mulai;
        $this->akhir   = $akhir;
        $this->salesId = $salesId;
    }

    public function collection()
    {
        return Setoran::with(['sales', 'detail.toko'])
            ->where('status', 'acc')
            ->whereBetween('tanggal', [$this->mulai, $this->akhir])
            ->when($this->salesId, fn($q) => $q->where('sales_id', $this->salesId))
            ->orderBy('tanggal')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Nama Sales',
            'No. HP Sales',
            'Jumlah Toko',
            'Total Unit Terjual',
            'Total Uang (Rp)',
            'Waktu Dikirim',
            'Waktu ACC',
        ];
    }

    public function map($setoran): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $setoran->tanggal->format('d/m/Y'),
            $setoran->sales->nama,
            $setoran->sales->no_hp ?? '-',
            $setoran->detail->count(),
            $setoran->totalTerjual(),
            $setoran->totalUang(),
            $setoran->dikirim_at?->format('d/m/Y H:i') ?? '-',
            $setoran->acc_at?->format('d/m/Y H:i') ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Baris header (baris 1)
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '16A34A']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Setoran';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $lastRow    = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Border semua sel data
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Warna baris genap (zebra stripe)
                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$lastColumn}{$row}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('F0FDF4');
                    }
                }

                // Format kolom Total Uang sebagai angka
                $sheet->getStyle("G2:G{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Freeze baris header
                $sheet->freezePane('A2');
            },
        ];
    }
}