<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PerformaExport;
use App\Exports\SetoranExport;
use App\Http\Controllers\Controller;
use App\Models\Setoran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    // ── Export Setoran ke Excel ────────────────────
    public function setoranExcel(Request $request)
    {
        [$mulai, $akhir] = $this->getPeriode($request);

        $namaFile = 'setoran_' . $mulai . '_' . $akhir . '.xlsx';

        return Excel::download(
            new SetoranExport($mulai, $akhir, $request->sales_id),
            $namaFile
        );
    }

    // ── Export Setoran ke PDF ──────────────────────
    public function setoranPdf(Request $request)
    {
        [$mulai, $akhir] = $this->getPeriode($request);

        $setoran = Setoran::with(['sales', 'detail.toko'])
            ->where('status', 'acc')
            ->whereBetween('tanggal', [$mulai, $akhir])
            ->when($request->sales_id, fn($q) => $q->where('sales_id', $request->sales_id))
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('pdf.setoran', compact('setoran', 'mulai', 'akhir'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('setoran_' . $mulai . '_' . $akhir . '.pdf');
    }

    // ── Export Performa ke Excel ───────────────────
    public function performaExcel(Request $request)
    {
        [$mulai, $akhir] = $this->getPeriode($request);

        return Excel::download(
            new PerformaExport($mulai, $akhir),
            'performa_sales_' . $mulai . '_' . $akhir . '.xlsx'
        );
    }

    // ── Helper periode ─────────────────────────────
    private function getPeriode(Request $request): array
    {
        $mulai = $request->get('mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $akhir = $request->get('akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));
        return [$mulai, $akhir];
    }
}