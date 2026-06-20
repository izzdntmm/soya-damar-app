<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    // ── INDEX — Lihat semua toko ───────────────────
    public function index(Request $request)
    {
        $search   = $request->get('search');
        $salesId  = $request->get('sales_id');

        $toko = Toko::with('sales')
            ->when($search, function ($q) use ($search) {
                $q->where('nama_toko', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            })
            ->when($salesId, function ($q) use ($salesId) {
                $q->where('sales_id', $salesId);
            })
            ->latest()
            ->paginate(12);

        // Daftar sales untuk filter dropdown
        $listSales = User::where('role', 'sales')
            ->orderBy('nama')
            ->get();

        return view('admin.toko.index', compact(
            'toko', 'search', 'salesId', 'listSales'
        ));
    }

    // ── SHOW — Detail satu toko ────────────────────
    public function show(Toko $toko)
    {
        $toko->load('sales');

        // Riwayat penjualan toko ini
        $riwayat = \App\Models\DetailSetoran::with(['setoran.sales'])
            ->where('toko_id', $toko->id)
            ->latest()
            ->take(10)
            ->get();

        $totalTerjual = \App\Models\DetailSetoran::where('toko_id', $toko->id)->sum('jumlah_terjual');
        $totalUang    = \App\Models\DetailSetoran::where('toko_id', $toko->id)->sum('total_uang');

        return view('admin.toko.show', compact(
            'toko', 'riwayat', 'totalTerjual', 'totalUang'
        ));
    }
}