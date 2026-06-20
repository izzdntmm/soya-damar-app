<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Setoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $salesId = Auth::id();

        // Data untuk dashboard sales (nanti kita lengkapi di Part 6-7)
        $setoranHariIni = Setoran::where('sales_id', $salesId)
            ->whereDate('tanggal', today())
            ->first();

        $totalToko = Auth::user()->toko()->count();

        return view('sales.dashboard', compact(
            'setoranHariIni',
            'totalToko'
        ));
    }
}