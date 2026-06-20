<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailSetoran;
use App\Models\Setoran;
use App\Models\Toko;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerformaController extends Controller
{
    public function index(Request $request)
    {
        // ── Periode Filter ─────────────────────────────
        $periode    = $request->get('periode', 'bulan_ini');
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalAkhir = $request->get('tanggal_akhir');

        [$mulai, $akhir] = $this->getPeriode(
            $periode, $tanggalMulai, $tanggalAkhir
        );

        // ── Ranking Sales ──────────────────────────────
        $rankingSales = User::where('role', 'sales')
            ->get()
            ->map(function ($sales) use ($mulai, $akhir) {

                // Total uang dari setoran ACC dalam periode
                $totalUang = DetailSetoran::whereHas('setoran', function ($q) use ($sales, $mulai, $akhir) {
                    $q->where('sales_id', $sales->id)
                      ->where('status', 'acc')
                      ->whereBetween('tanggal', [$mulai, $akhir]);
                })->sum('total_uang');

                // Total unit terjual
                $totalUnit = DetailSetoran::whereHas('setoran', function ($q) use ($sales, $mulai, $akhir) {
                    $q->where('sales_id', $sales->id)
                      ->where('status', 'acc')
                      ->whereBetween('tanggal', [$mulai, $akhir]);
                })->sum('jumlah_terjual');

                // Jumlah setoran ACC
                $jumlahSetoran = Setoran::where('sales_id', $sales->id)
                    ->where('status', 'acc')
                    ->whereBetween('tanggal', [$mulai, $akhir])
                    ->count();

                // Jumlah toko aktif (yang pernah setor)
                $tokoAktif = DetailSetoran::whereHas('setoran', function ($q) use ($sales, $mulai, $akhir) {
                    $q->where('sales_id', $sales->id)
                      ->where('status', 'acc')
                      ->whereBetween('tanggal', [$mulai, $akhir]);
                })->distinct('toko_id')->count('toko_id');

                $sales->total_uang      = (float) $totalUang;
                $sales->total_unit      = (int)   $totalUnit;
                $sales->jumlah_setoran  = (int)   $jumlahSetoran;
                $sales->toko_aktif      = (int)   $tokoAktif;

                return $sales;
            })
            ->sortByDesc('total_uang')
            ->values();

        // ── Highlight Cards ────────────────────────────

        // Sales terbaik
        $salesTerbaik = $rankingSales->first();

        // Toko paling laris dalam periode
        $tokoPaling = DetailSetoran::select('toko_id', DB::raw('SUM(jumlah_terjual) as total_terjual'), DB::raw('SUM(total_uang) as total_uang'))
            ->whereHas('setoran', function ($q) use ($mulai, $akhir) {
                $q->where('status', 'acc')
                  ->whereBetween('tanggal', [$mulai, $akhir]);
            })
            ->groupBy('toko_id')
            ->orderByDesc('total_terjual')
            ->with('toko')
            ->first();

        // Hari penjualan tertinggi
        $hariTertinggi = Setoran::select('tanggal', DB::raw('COUNT(*) as jumlah_setoran'))
            ->where('status', 'acc')
            ->whereBetween('tanggal', [$mulai, $akhir])
            ->groupBy('tanggal')
            ->orderByDesc('jumlah_setoran')
            ->first();

        // ── Data Grafik Bar: Perbandingan Sales ────────
        $grafikBar = $rankingSales->map(fn($s) => [
            'nama'       => explode(' ', $s->nama)[0], // Nama depan saja
            'total_uang' => $s->total_uang,
            'total_unit' => $s->total_unit,
        ])->take(8); // Maksimal 8 sales di grafik

        // ── Data Grafik Line: Tren 14 Hari ────────────
        $grafikLine = collect();
        $days       = $mulai->diffInDays($akhir) > 14 ? 14 : $mulai->diffInDays($akhir);

        for ($i = $days; $i >= 0; $i--) {
            $tgl = $akhir->copy()->subDays($i);

            $totalHari = DetailSetoran::whereHas('setoran', function ($q) use ($tgl) {
                $q->where('status', 'acc')
                  ->whereDate('tanggal', $tgl);
            })->sum('total_uang');

            $grafikLine->push([
                'tanggal' => $tgl->format('d M'),
                'total'   => (float) $totalHari,
            ]);
        }

        // ── Top 5 Toko Terlaris ────────────────────────
        $topToko = DetailSetoran::select(
                'toko_id',
                DB::raw('SUM(jumlah_terjual) as total_terjual'),
                DB::raw('SUM(total_uang) as total_uang')
            )
            ->whereHas('setoran', function ($q) use ($mulai, $akhir) {
                $q->where('status', 'acc')
                  ->whereBetween('tanggal', [$mulai, $akhir]);
            })
            ->groupBy('toko_id')
            ->orderByDesc('total_terjual')
            ->with('toko.sales')
            ->take(5)
            ->get();

        // ── Total Keseluruhan Periode ──────────────────
        $totalPeriode = [
            'uang'    => $rankingSales->sum('total_uang'),
            'unit'    => $rankingSales->sum('total_unit'),
            'setoran' => $rankingSales->sum('jumlah_setoran'),
        ];

        return view('admin.performa.index', compact(
            'rankingSales',
            'salesTerbaik',
            'tokoPaling',
            'hariTertinggi',
            'grafikBar',
            'grafikLine',
            'topToko',
            'totalPeriode',
            'periode',
            'mulai',
            'akhir',
            'tanggalMulai',
            'tanggalAkhir'
        ));
    }

    // ── Detail performa satu sales (AJAX/partial) ──
    public function detail(Request $request, User $sale)
    {
        abort_if($sale->role !== 'sales', 404);

        $periode = $request->get('periode', 'bulan_ini');
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalAkhir = $request->get('tanggal_akhir');

        [$mulai, $akhir] = $this->getPeriode(
            $periode, $tanggalMulai, $tanggalAkhir
        );

        // Breakdown per toko untuk sales ini
        $breakdownToko = DetailSetoran::select(
                'toko_id',
                DB::raw('SUM(jumlah_terjual) as total_terjual'),
                DB::raw('SUM(total_uang) as total_uang'),
                DB::raw('COUNT(*) as frekuensi')
            )
            ->whereHas('setoran', function ($q) use ($sale, $mulai, $akhir) {
                $q->where('sales_id', $sale->id)
                  ->where('status', 'acc')
                  ->whereBetween('tanggal', [$mulai, $akhir]);
            })
            ->groupBy('toko_id')
            ->orderByDesc('total_terjual')
            ->with('toko')
            ->get();

        // Tren harian sales ini
        $trenHarian = collect();
        for ($i = 13; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);

            $totalHari = DetailSetoran::whereHas('setoran', function ($q) use ($sale, $tgl) {
                $q->where('sales_id', $sale->id)
                  ->where('status', 'acc')
                  ->whereDate('tanggal', $tgl);
            })->sum('total_uang');

            $trenHarian->push([
                'tanggal' => $tgl->format('d M'),
                'total'   => (float) $totalHari,
            ]);
        }

        return response()->json([
            'sales'          => $sale->only(['nama', 'email', 'no_hp']),
            'breakdown_toko' => $breakdownToko,
            'tren_harian'    => $trenHarian,
        ]);
    }

    // ── Helper: Hitung rentang tanggal ────────────
    private function getPeriode(
        string $periode,
        ?string $mulaiCustom,
        ?string $akhirCustom
    ): array {
        return match ($periode) {
            'minggu_ini'  => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'bulan_lalu'  => [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()],
            'tahun_ini'   => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            'custom'      => [
                Carbon::parse($mulaiCustom ?? today()),
                Carbon::parse($akhirCustom ?? today()),
            ],
            default       => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()], // bulan_ini
        };
    }
}