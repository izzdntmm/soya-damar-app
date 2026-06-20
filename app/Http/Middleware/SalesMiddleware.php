<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SalesMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek: sudah login?
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek: rolenya sales?
        if (Auth::user()->role !== 'sales') {
            // Kalau bukan sales, lempar ke dashboard admin
            return redirect('/admin/dashboard')
                ->with('error', 'Anda tidak punya akses ke halaman Sales.');
        }

        return $next($request);
    }
}