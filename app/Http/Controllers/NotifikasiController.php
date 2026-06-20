<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    // Tandai satu notifikasi sebagai dibaca
    public function baca(Notifikasi $notifikasi)
    {
        abort_if($notifikasi->user_id !== Auth::id(), 403);

        $notifikasi->update(['dibaca_at' => now()]);

        // Redirect ke URL tujuan kalau ada
        if ($notifikasi->url) {
            return redirect($notifikasi->url);
        }

        return back();
    }

    // Tandai semua notif sebagai dibaca
    public function bacaSemua()
    {
        Notifikasi::where('user_id', Auth::id())
            ->belumDibaca()
            ->update(['dibaca_at' => now()]);

        return back()->with('success', 'Semua notifikasi sudah ditandai dibaca.');
    }

    // Hapus notifikasi
    public function hapus(Notifikasi $notifikasi)
    {
        abort_if($notifikasi->user_id !== Auth::id(), 403);
        $notifikasi->delete();

        return back();
    }

    // API: jumlah notif belum dibaca (untuk polling)
    public function count()
    {
        return response()->json([
            'count' => Notifikasi::where('user_id', Auth::id())
                ->belumDibaca()
                ->count()
        ]);
    }
}