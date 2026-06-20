<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesProfileController extends Controller
{
    public function edit()
    {
        return view('sales.profile.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $user = Auth::user();

        $user->nama = $request->nama;
        $user->email = $request->email;

        $user->save();

        return back()->with('success', 'Profile berhasil diperbarui');
    }
}