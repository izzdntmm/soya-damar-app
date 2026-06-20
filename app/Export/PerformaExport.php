<?php

namespace App\Exports;

use App\Models\DetailSetoran;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
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

class PerformaExport implements
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

    public function __construct(string $mulai, string $akhir)
    {
        $this->mulai = $mulai;
        $this->akhir = $akhir;
    }

    public function collection()
    {
        return User::where('role', 'sales')
            ->withCount('toko')
            ->get()
            ->map(function ($sales) {
                $totalUang = DetailSetoran::whereHas('setoran', function ($q) use ($sales) {
                    $q->where('sales_id', $sales->id)
                      ->where('status', 'acc')
                      ->whereBetween('tanggal', [$this->mulai, $this->akhir]);
                })->sum('total_uang');

                $totalUnit = DetailSetoran::whereHas('setoran', function ($q) use ($sales) {
                    $q->where('sales_id', $sales->id)
                      ->where('status', 'acc')
                      ->whereBetween('tanggal', [$this->mulai, $this->akhir]);
                })->sum('jumlah_terjual');

                $jumlahSetoran = \App\Models\Setoran::where('sales_id', $sales->id)
                    ->where('status', 'acc')
                    ->whereBetween('tanggal', [$this->mulai, $this->akhir])
                    ->count();

                $sales->total_uang     = $totalUang;
                $sales->total_unit     = $totalUnit;
                $sales->jml_setoran    = $jumlahSetoran;
                return $sales;
            })
            ->sortByDesc('total_uang')
            ->values();
    }

    public function headings(): array
    {
        return [
            'Rank',
            'Nama Sales',
            'No. HP',
            'Total Toko',
            'Setoran ACC',
            'Total Unit',
            'Total Penjualan (Rp)',
        ];
    }

    public function map($sales): array
    {
        static $rank = 0;
        $rank++;

        return [
            $rank,
            $sales->nama,
            $sales->no_hp ?? '-',
            $sales->toko_count,
            $sales->jml_setoran,
            $sales->total_unit,
            $sales->total_uang,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1D4ED8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Rekap Performa Sales';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle("A1:G{$lastRow}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle("G2:G{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                $sheet->freezePane('A2');
            },
        ];
    }
}