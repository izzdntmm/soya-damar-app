<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    /**
     * Menyimpan atau memperbarui subskripsi push perangkat user.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'endpoint'    => 'required|url',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);

        $user = Auth::user();

        // Menyimpan atau memperbarui token perangkat ke tabel push_subscriptions via package webpush
        $user->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth']
        );

        return response()->json([
            'success' => true,
            'message' => 'Token perangkat berhasil disimpan.'
        ]);
    }
}