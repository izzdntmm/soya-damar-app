<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailSetoran;
use App\Models\Setoran;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\NotifikasiService;

class SetoranController extends Controller
{
    // ── INDEX — Daftar semua setoran ───────────────
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal');
        $status = $request->get('status');
        $salesId = $request->get('sales_id');

        $setoran = Setoran::with(['sales', 'detail'])
            ->when(
                $tanggal,
                fn($q) =>
                $q->whereDate('tanggal', $tanggal)
            )
            ->when(
                $status,
                fn($q) =>
                $q->where('status', $status)
            )
            ->when(
                $salesId,
                fn($q) =>
                $q->where('sales_id', $salesId)
            )
            ->latest('tanggal')
            ->paginate(15);

        // Kartu ringkasan
        $ringkasan = [
            'semua' => Setoran::count(),
            'draft' => Setoran::where('status', 'draft')->count(),
            'dikirim' => Setoran::where('status', 'dikirim')->count(),
            'acc' => Setoran::where('status', 'acc')->count(),
        ];

        // Dropdown filter sales
        $listSales = User::where('role', 'sales')
            ->orderBy('nama')
            ->get();

        return view('admin.setoran.index', compact(
            'setoran',
            'ringkasan',
            'listSales',
            'tanggal',
            'status',
            'salesId'
        ));
    }

    // ── SHOW — Detail satu setoran ─────────────────
    public function show(Setoran $setoran)
    {
        $setoran->load(['sales', 'detail.toko']);

        return view('admin.setoran.show', compact('setoran'));
    }

    // Update method acc()
    public function acc(Setoran $setoran)
    {
        if ($setoran->status !== 'dikirim') {
            return back()->with('error', 'Hanya setoran berstatus "Dikirim" yang bisa dikonfirmasi.');
        }

        $setoran->update([
            'status' => 'acc',
            'acc_at' => now(),
        ]);

        // ── Kirim notifikasi ke sales ──────────────
        $setoran->load('sales', 'detail');
        NotifikasiService::adminAccSetoran($setoran);

        return back()->with(
            'success',
            "Setoran {$setoran->sales->nama} ({$setoran->tanggal->format('d M Y')}) berhasil dikonfirmasi!"
        );
    }

    // Update method tolak()
    public function tolak(Setoran $setoran)
    {
        if ($setoran->status !== 'dikirim') {
            return back()->with('error', 'Hanya setoran berstatus "Dikirim" yang bisa ditolak.');
        }

        $setoran->update([
            'status' => 'draft',
            'dikirim_at' => null,
        ]);

        // ── Kirim notifikasi ke sales ──────────────
        $setoran->load('sales', 'detail');
        NotifikasiService::adminTolakSetoran($setoran);

        return back()->with(
            'success',
            "Setoran dikembalikan ke draft. Sales bisa mengedit ulang."
        );
    }

    // ── ACC MASSAL — Konfirmasi banyak sekaligus ───
    public function accMassal(Request $request)
    {
        $request->validate([
            'setoran_ids' => 'required|array|min:1',
            'setoran_ids.*' => 'exists:setoran,id',
        ], [
            'setoran_ids.required' => 'Pilih minimal 1 setoran.',
        ]);

        $jumlah = Setoran::whereIn('id', $request->setoran_ids)
            ->where('status', 'dikirim')
            ->update([
                'status' => 'acc',
                'acc_at' => now(),
            ]);

        return back()->with(
            'success',
            "{$jumlah} setoran berhasil dikonfirmasi sekaligus!"
        );
    }
}