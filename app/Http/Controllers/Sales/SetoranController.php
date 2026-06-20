<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\DetailSetoran;
use App\Models\Setoran;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotifikasiService;

class SetoranController extends Controller
{
    // ── INDEX — Halaman utama setoran harian ───────
    public function index()
    {
        $salesId = Auth::id();

        // Ambil atau buat setoran hari ini (status draft)
        $setoran = Setoran::with(['detail.toko'])
            ->where('sales_id', $salesId)
            ->whereDate('tanggal', today())
            ->first();

        // Daftar toko milik sales ini
        $listToko = Toko::where('sales_id', $salesId)
            ->orderBy('nama_toko')
            ->get();

        // Harga satuan dari config
        $hargaSatuan = config('soya.harga_satuan');

        // Riwayat setoran sebelumnya (selain hari ini)
        $riwayat = Setoran::with(['detail'])
            ->where('sales_id', $salesId)
            ->whereDate('tanggal', '!=', today())
            ->latest('tanggal')
            ->take(7)
            ->get();

        return view('sales.setoran.index', compact(
            'setoran',
            'listToko',
            'hargaSatuan',
            'riwayat'
        ));
    }

    // ── STORE — Buat setoran baru hari ini ─────────
    public function store(Request $request)
    {
        $salesId = Auth::id();

        // Cegah duplikat: cek sudah ada setoran hari ini belum
        $sudahAda = Setoran::where('sales_id', $salesId)
            ->whereDate('tanggal', today())
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Setoran hari ini sudah ada.');
        }

        Setoran::create([
            'sales_id' => $salesId,
            'tanggal' => today(),
            'status' => 'draft',
        ]);

        return back()->with('success', 'Setoran hari ini berhasil dibuat. Silakan input detail per toko.');
    }

    // ── STORE DETAIL — Tambah item toko ke setoran ─
    public function storeDetail(Request $request, Setoran $setoran)
    {
        // Pastikan setoran ini milik sales yang login
        abort_if($setoran->sales_id !== Auth::id(), 403);

        // Tidak bisa edit kalau sudah dikirim/acc
        if ($setoran->status !== 'draft') {
            return back()->with('error', 'Setoran yang sudah dikirim tidak bisa diubah.');
        }

        $validated = $request->validate([
            'toko_id' => 'required|exists:toko,id',
            'jumlah_terjual' => 'required|integer|min:1|max:9999',
        ], [
            'toko_id.required' => 'Pilih toko terlebih dahulu.',
            'toko_id.exists' => 'Toko tidak ditemukan.',
            'jumlah_terjual.required' => 'Jumlah terjual wajib diisi.',
            'jumlah_terjual.min' => 'Jumlah terjual minimal 1.',
            'jumlah_terjual.integer' => 'Jumlah terjual harus berupa angka.',
        ]);

        // Validasi: toko ini milik sales yang login
        $toko = Toko::where('id', $validated['toko_id'])
            ->where('sales_id', Auth::id())
            ->firstOrFail();

        // Cek apakah toko ini sudah ada di setoran hari ini
        $sudahAda = DetailSetoran::where('setoran_id', $setoran->id)
            ->where('toko_id', $toko->id)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', "Toko \"{$toko->nama_toko}\" sudah diinput hari ini.");
        }

        // Hitung otomatis
        $hargaSatuan = config('soya.harga_satuan');
        $totalUang = $validated['jumlah_terjual'] * $hargaSatuan;

        DetailSetoran::create([
            'setoran_id' => $setoran->id,
            'toko_id' => $toko->id,
            'jumlah_terjual' => $validated['jumlah_terjual'],
            'harga_satuan' => $hargaSatuan,
            'total_uang' => $totalUang,
        ]);

        return back()->with('success', "{$toko->nama_toko} — {$validated['jumlah_terjual']} unit berhasil ditambahkan.");
    }

    // ── UPDATE DETAIL — Edit jumlah item ───────────
    public function updateDetail(Request $request, Setoran $setoran, DetailSetoran $detail)
    {
        abort_if($setoran->sales_id !== Auth::id(), 403);
        abort_if($detail->setoran_id !== $setoran->id, 403);

        if ($setoran->status !== 'draft') {
            return back()->with('error', 'Setoran yang sudah dikirim tidak bisa diubah.');
        }

        $validated = $request->validate([
            'jumlah_terjual' => 'required|integer|min:1|max:9999',
        ]);

        $hargaSatuan = config('soya.harga_satuan');

        $detail->update([
            'jumlah_terjual' => $validated['jumlah_terjual'],
            'harga_satuan' => $hargaSatuan,
            'total_uang' => $validated['jumlah_terjual'] * $hargaSatuan,
        ]);

        return back()->with('success', 'Jumlah berhasil diperbarui.');
    }

    // ── DESTROY DETAIL — Hapus item dari setoran ───
    public function destroyDetail(Setoran $setoran, DetailSetoran $detail)
    {
        abort_if($setoran->sales_id !== Auth::id(), 403);
        abort_if($detail->setoran_id !== $setoran->id, 403);

        if ($setoran->status !== 'draft') {
            return back()->with('error', 'Setoran yang sudah dikirim tidak bisa diubah.');
        }

        $detail->delete();

        return back()->with('success', 'Item berhasil dihapus dari setoran.');
    }

    // ── KIRIM — Ubah status draft → dikirim ────────
    // Update method kirim()
    public function kirim(Setoran $setoran)
    {
        abort_if($setoran->sales_id !== Auth::id(), 403);

        if ($setoran->status !== 'draft') {
            return back()->with('error', 'Setoran ini sudah dikirim sebelumnya.');
        }

        if ($setoran->detail()->count() === 0) {
            return back()->with('error', 'Tambahkan minimal 1 toko sebelum mengirim laporan.');
        }

        $setoran->update([
            'status' => 'dikirim',
            'dikirim_at' => now(),
        ]);

        // ── Kirim notifikasi ke admin ──────────────
        $setoran->load('sales', 'detail');
        NotifikasiService::salesKirimLaporan($setoran);

        return back()->with('success', 'Laporan berhasil dikirim! Menunggu konfirmasi admin.');
    }

    // ── RIWAYAT — Semua riwayat setoran sales ──────
    public function riwayat()
    {
        $setoran = Setoran::with(['detail.toko'])
            ->where('sales_id', Auth::id())
            ->latest('tanggal')
            ->paginate(10);

        return view('sales.setoran.riwayat', compact('setoran'));
    }
}