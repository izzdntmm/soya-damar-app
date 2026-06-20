<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SalesController extends Controller
{
    // ── INDEX — Daftar semua sales ─────────────────
    public function index(Request $request)
    {
        $search = $request->get('search');

        $sales = User::where('role', 'sales')
            ->when($search, function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%");
            })
            ->withCount('toko')
            ->withCount([
                'setoran as total_setoran_acc' => function ($q) {
                    $q->where('status', 'acc');
                }
            ])
            ->latest()
            ->paginate(10);

        return view('admin.sales.index', compact('sales', 'search'));
    }

    // ── CREATE — Form tambah sales ─────────────────
    public function create()
    {
        return view('admin.sales.create');
    }

    // ── STORE — Simpan sales baru ──────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'password' => ['required', Password::min(8)],
        ], [
            'nama.required' => 'Nama sales wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan akun lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'role' => 'sales',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.sales.index')
            ->with('success', "Sales \"{$validated['nama']}\" berhasil ditambahkan.");
    }

    // ── EDIT — Form edit sales ─────────────────────
    public function edit(User $sale)
    {
        // Pastikan yang diedit adalah role sales
        abort_if($sale->role !== 'sales', 404);

        return view('admin.sales.edit', compact('sale'));
    }

    // ── UPDATE — Simpan perubahan data sales ───────
    public function update(Request $request, User $sale)
    {
        abort_if($sale->role !== 'sales', 404);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => "required|email|unique:users,email,{$sale->id}",
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'password' => ['nullable', Password::min(8)],
        ], [
            'nama.required' => 'Nama sales wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan akun lain.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $data = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
        ];

        // Hanya update password kalau diisi
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $sale->update($data);

        return redirect()
            ->route('admin.sales.index')
            ->with('success', "Data sales \"{$sale->nama}\" berhasil diperbarui.");
    }

    // ── DESTROY — Hapus sales ──────────────────────
    public function destroy(User $sale)
    {
        abort_if($sale->role !== 'sales', 404);

        // Cek apakah sales punya setoran yang sudah ACC
        $adaSetoranAcc = $sale->setoran()->where('status', 'acc')->exists();

        if ($adaSetoranAcc) {
            return back()->with(
                'error',
                "Sales \"{$sale->nama}\" tidak bisa dihapus karena memiliki riwayat setoran yang sudah dikonfirmasi. " .
                "Data keuangan tidak boleh dihilangkan."
            );
        }

        $nama = $sale->nama;
        $sale->delete();

        return redirect()
            ->route('admin.sales.index')
            ->with('success', "Sales \"{$nama}\" berhasil dihapus.");
    }

    // ── SHOW — Detail sales ────────────────────────
    public function show(User $sale)
    {
        abort_if($sale->role !== 'sales', 404);

        $sale->loadCount('toko');

        // Total penjualan sales ini
        $totalUang = \App\Models\DetailSetoran::whereHas('setoran', function ($q) use ($sale) {
            $q->where('sales_id', $sale->id)->where('status', 'acc');
        })->sum('total_uang');

        // Riwayat setoran terbaru
        $riwayatSetoran = $sale->setoran()
            ->with('detail')
            ->latest('tanggal')
            ->take(10)
            ->get();

        return view('admin.sales.show', compact('sale', 'totalUang', 'riwayatSetoran'));
    }
}