<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Store;
use App\Models\Delivery;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller
{
    /**
     * Halaman index laporan semua sales (rekap jumlah setoran per sales)
     */
    public function index()
    {
        auth()->user()->unreadNotifications->markAsRead();

        // Ambil semua sales yang punya setoran yang sudah disubmit
        $sales = User::whereHas('deliveries', function ($q) {
            $q->where('is_submitted', true);
        })->withCount([
                    'deliveries as submitted_count' => function ($q) {
                        $q->where('is_submitted', true);
                    }
                ])->get();

        // Ambil notifikasi untuk admin yang sedang login
        $notifications = auth()->user()->notifications()->latest()->take(10)->get();

        // Ambil data laporan harian (jika ada)
        $deliveries = Delivery::with('user', 'store')
            ->where('is_submitted', true)
            ->latest('delivery_date')
            ->get();

        return view('admin.reports.index', compact('sales', 'notifications', 'deliveries'));
    }



    /**
     * Detail laporan setoran per sales (menampilkan semua penyetoran dari satu sales)
     */
    public function show(Request $request, User $user)
    {
        // Ambil tanggal dari form, default ke hari ini
        $tanggal = $request->input('tanggal', now()->toDateString());

        // Ambil semua delivery berdasarkan user, tanggal, dan sudah disubmit
        $deliveries = $user->deliveries()
            ->where('is_submitted', true)
            ->whereDate('delivery_date', $tanggal)
            ->with('store')
            ->get();

        // Hitung total quantity dan total price
        $totalBarang = $deliveries->sum('quantity');
        $totalHarga = $deliveries->sum('total_price');

        return view('admin.reports.show', compact('user', 'deliveries', 'tanggal', 'totalBarang', 'totalHarga'));
    }

    /**
     * Laporan Penjualan Bulanan per Sales + Grafik
     */
    public function monthly(Request $request)
    {
        $sales = User::where('role', 'sales')->get();

        $selectedSalesId = $request->input('sales_id');
        $selectedMonth = $request->input('month') ?? now()->format('Y-m'); // default ke bulan ini

        $startDate = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $endDate = Carbon::parse($selectedMonth . '-01')->endOfMonth();

        $query = DB::table('deliveries')
            ->join('stores', 'deliveries.store_id', '=', 'stores.id')
            ->join('users', 'deliveries.user_id', '=', 'users.id')
            ->select(
                'stores.name as store_name',
                DB::raw('SUM(deliveries.quantity) as total_barang'),
                DB::raw('SUM(deliveries.total_price) as total_uang')
            )
            ->where('is_submitted', true)
            ->whereBetween('delivery_date', [$startDate, $endDate]);

        if ($selectedSalesId) {
            $query->where('deliveries.user_id', $selectedSalesId);
        }

        $results = $query->groupBy('stores.name')->get();

        return view('admin.reports.monthly', compact(
            'sales',
            'results',
            'selectedSalesId',
            'selectedMonth'
        ));
    }

    public function exportMonthlyPDF(Request $request)
    {
        $sales = User::where('role', 'sales')->get();

        $selectedSalesId = $request->input('sales_id');
        $selectedMonth = $request->input('month') ?? now()->format('Y-m');

        $startDate = Carbon::parse($selectedMonth . '-01')->startOfMonth();
        $endDate = Carbon::parse($selectedMonth . '-01')->endOfMonth();

        $query = DB::table('deliveries')
            ->join('stores', 'deliveries.store_id', '=', 'stores.id')
            ->join('users', 'deliveries.user_id', '=', 'users.id')
            ->select(
                'stores.name as store_name',
                DB::raw('SUM(deliveries.quantity) as total_barang'),
                DB::raw('SUM(deliveries.total_price) as total_uang')
            )
            ->where('is_submitted', true)
            ->whereBetween('delivery_date', [$startDate, $endDate]);

        if ($selectedSalesId) {
            $query->where('deliveries.user_id', $selectedSalesId);
        }

        $results = $query->groupBy('stores.name')->get();

        // Generate PDF
        $pdf = Pdf::loadView('pdf.laporan-bulanan', [
            'sales' => $sales,
            'results' => $results,
            'selectedSalesId' => $selectedSalesId,
            'selectedMonth' => $selectedMonth,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('laporan-bulanan.pdf');
    }

}
