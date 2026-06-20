<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailSetoran;
use App\Models\Setoran;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Kartu Statistik ───────────────────────────
        $totalSales   = User::where('role', 'sales')->count();
        $totalSetoran = Setoran::count();
        $menungguAcc  = Setoran::where('status', 'dikirim')->count();

        // Total uang masuk hari ini (yang sudah di-acc)
        $totalUangHariIni = DetailSetoran::whereHas('setoran', function ($q) {
            $q->whereDate('tanggal', today())
              ->where('status', 'acc');
        })->sum('total_uang');

        // ── Grafik Penjualan 7 Hari Terakhir ──────────
        $grafikData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);

            $totalHari = DetailSetoran::whereHas('setoran', function ($q) use ($tanggal) {
                $q->whereDate('tanggal', $tanggal)
                  ->where('status', 'acc');
            })->sum('total_uang');

            $grafikData->push([
                'tanggal' => $tanggal->format('d M'),
                'total'   => (float) $totalHari,
            ]);
        }

        // ── Top 5 Sales (Ranking) ─────────────────────
        $topSales = User::where('role', 'sales')
            ->withSum(['setoran as total_penjualan' => function ($q) {
                $q->where('status', 'acc')
                  ->with('detail');
            }], 'id')
            ->get()
            ->map(function ($sales) {
                $sales->total_uang = DetailSetoran::whereHas('setoran', function ($q) use ($sales) {
                    $q->where('sales_id', $sales->id)
                      ->where('status', 'acc');
                })->sum('total_uang');
                return $sales;
            })
            ->sortByDesc('total_uang')
            ->take(5);

        // ── Setoran Pending (Menunggu Konfirmasi) ─────
        $setoranPending = Setoran::with(['sales', 'detail'])
            ->where('status', 'dikirim')
            ->latest('dikirim_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalSetoran',
            'menungguAcc',
            'totalUangHariIni',
            'grafikData',
            'topSales',
            'setoranPending'
        ));
    }
}