<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek: sudah login?
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek: rolenya admin?
        if (Auth::user()->role !== 'admin') {
            // Kalau bukan admin, lempar ke dashboard sales
            return redirect('/sales/dashboard')
                ->with('error', 'Anda tidak punya akses ke halaman Admin.');
        }

        return $next($request);
    }
}