<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokoController extends Controller
{
    // ── INDEX — Toko milik sales ini ──────────────
    public function index(Request $request)
    {
        $search = $request->get('search');

        $toko = Toko::where('sales_id', Auth::id())
            ->when($search, function ($q) use ($search) {
                $q->where('nama_toko', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12);

        return view('sales.toko.index', compact('toko', 'search'));
    }

    // ── CREATE — Form tambah toko ──────────────────
    public function create()
    {
        return view('sales.toko.create');
    }

    // ── STORE — Simpan toko baru ───────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ], [
            'nama_toko.required' => 'Nama toko wajib diisi.',
        ]);

        Toko::create([
            'sales_id' => Auth::id(),
            'nama_toko' => $validated['nama_toko'],
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        return redirect()
            ->route('sales.toko.index')
            ->with('success', "Toko \"{$validated['nama_toko']}\" berhasil ditambahkan.");
    }

    // ── EDIT — Form edit toko ──────────────────────
    public function edit(Toko $toko)
    {
        // Pastikan toko ini milik sales yang sedang login
        abort_if($toko->sales_id !== Auth::id(), 403);

        return view('sales.toko.edit', compact('toko'));
    }

    // ── UPDATE — Simpan perubahan ──────────────────
    public function update(Request $request, Toko $toko)
    {
        abort_if($toko->sales_id !== Auth::id(), 403);

        $validated = $request->validate([
            'nama_toko' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ], [
            'nama_toko.required' => 'Nama toko wajib diisi.',
        ]);

        $toko->update($validated);

        return redirect()
            ->route('sales.toko.index')
            ->with('success', "Toko \"{$toko->nama_toko}\" berhasil diperbarui.");
    }

    // ── DESTROY — Hapus toko ───────────────────────
    public function destroy(Toko $toko)
    {
        abort_if($toko->sales_id !== Auth::id(), 403);

        // Cek apakah toko ini punya riwayat transaksi
        $adaRiwayat = \App\Models\DetailSetoran::where('toko_id', $toko->id)->exists();

        if ($adaRiwayat) {
            return back()->with(
                'error',
                "Toko \"{$toko->nama_toko}\" tidak bisa dihapus karena memiliki riwayat setoran. " .
                "Arsipkan saja jika tidak aktif."
            );
        }

        $nama = $toko->nama_toko;
        $toko->delete();

        return redirect()
            ->route('sales.toko.index')
            ->with('success', "Toko \"{$nama}\" berhasil dihapus.");
    }
}