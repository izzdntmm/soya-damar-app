<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = auth()->user()->stores; // hanya toko milik sales ini
        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        return view('stores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        auth()->user()->stores()->create([
            'name' => $request->name,
            'address' => $request->address,
        ]);

        return redirect()->route('stores.index')->with('success', 'Toko berhasil ditambahkan.');
    }

    public function edit(Store $store)
    {
        if ($store->user_id !== auth()->id()) {
            abort(403);
        }

        return view('stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        if ($store->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        $store->update([
            'name' => $request->name,
            'address' => $request->address,
        ]);

        return redirect()->route('stores.index')->with('success', 'Toko berhasil diperbarui.');
    }

    public function destroy(Store $store)
    {
        if ($store->user_id !== auth()->id()) {
            abort(403);
        }

        $store->delete();

        return redirect()->route('stores.index')->with('success', 'Toko berhasil dihapus.');
    }
}
