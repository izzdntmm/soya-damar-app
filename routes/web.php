<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Sales;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sales\SalesProfileController;
use App\Models\Setoran;
use App\Models\Toko;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PushSubscriptionController;

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'update'])->name('push.subscribe');
});

Route::middleware('auth')->prefix('sales')->name('sales.')->group(function () {

    Route::get('/profile', [SalesProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [SalesProfileController::class, 'update'])
        ->name('profile.update');

});

// ── Halaman Utama ────────────────────────────────
Route::get('/', function () {
    return redirect('/login');
});

// ── Route Auth (login, logout) dari Breeze ───────
require __DIR__ . '/auth.php';

// ── Notifikasi (semua role) ───────────────────
Route::middleware(['auth'])->group(function () {
    Route::post('notifikasi/{notifikasi}/baca', [NotifikasiController::class, 'baca'])
        ->name('notifikasi.baca');
    Route::post('notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])
        ->name('notifikasi.baca-semua');
    Route::delete('notifikasi/{notifikasi}/hapus', [NotifikasiController::class, 'hapus'])
        ->name('notifikasi.hapus');
    Route::get('notifikasi/count', [NotifikasiController::class, 'count'])
        ->name('notifikasi.count');
});

// ════════════════════════════════════════════════
// ROUTE ADMIN
// Semua route di sini butuh: login + role admin
// ════════════════════════════════════════════════
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // ── Manajemen Sales ──────────────────────
        Route::resource('sales', Admin\SalesController::class);
        // Toko — admin hanya bisa lihat (index & show)
        Route::resource('toko', Admin\TokoController::class)
            ->only(['index', 'show']);
        // Route::resource('setoran', Admin\SetoranController::class);
    
        // ── Setoran ──────────────────────────────────
        // Index & Show (resource sebagian)
        Route::resource('setoran', Admin\SetoranController::class)
            ->only(['index', 'show']);

        // ACC satu setoran
        Route::post('setoran/{setoran}/acc', [Admin\SetoranController::class, 'acc'])
            ->name('setoran.acc');

        // Tolak / kembalikan ke draft
        Route::post('setoran/{setoran}/tolak', [Admin\SetoranController::class, 'tolak'])
            ->name('setoran.tolak');

        // ACC massal
        Route::post('setoran/acc-massal', [Admin\SetoranController::class, 'accMassal'])
            ->name('setoran.acc-massal');

        // ── Performa ─────────────────────────────────
        Route::get('/performa', [Admin\PerformaController::class, 'index'])
            ->name('performa.index');

        Route::get('/performa/{sale}/detail', [Admin\PerformaController::class, 'detail'])
            ->name('performa.detail');

        // ── Export ───────────────────────────────────
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/setoran/excel', [Admin\ExportController::class, 'setoranExcel'])
                ->name('setoran.excel');
            Route::get('/setoran/pdf', [Admin\ExportController::class, 'setoranPdf'])
                ->name('setoran.pdf');
            Route::get('/performa/excel', [Admin\ExportController::class, 'performaExcel'])
                ->name('performa.excel');
        });

    });

// ════════════════════════════════════════════════
// ROUTE SALES
// Semua route di sini butuh: login + role sales
// ════════════════════════════════════════════════
Route::prefix('sales')
    ->middleware(['auth', 'sales'])
    ->name('sales.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Sales\DashboardController::class, 'index'])
            ->name('dashboard');

        // Toko — sales bisa full CRUD
        Route::resource('toko', Sales\TokoController::class)
            ->except(['show']);
        // Route::resource('setoran', Sales\SetoranController::class);
    
        // ── Setoran ──────────────────────────────────
        // Halaman utama setoran hari ini
        Route::get('/setoran', [Sales\SetoranController::class, 'index'])
            ->name('setoran.index');

        // Buat setoran hari ini (kalau belum ada)
        Route::post('/setoran', [Sales\SetoranController::class, 'store'])
            ->name('setoran.store');

        // Kirim laporan (draft → dikirim)
        Route::post('/setoran/{setoran}/kirim', [Sales\SetoranController::class, 'kirim'])
            ->name('setoran.kirim');

        // CRUD detail item setoran
        Route::post('/setoran/{setoran}/detail', [Sales\SetoranController::class, 'storeDetail'])
            ->name('setoran.detail.store');

        Route::put('/setoran/{setoran}/detail/{detail}', [Sales\SetoranController::class, 'updateDetail'])
            ->name('setoran.detail.update');

        Route::delete('/setoran/{setoran}/detail/{detail}', [Sales\SetoranController::class, 'destroyDetail'])
            ->name('setoran.detail.destroy');

        // Riwayat semua setoran
        Route::get('/setoran/riwayat', [Sales\SetoranController::class, 'riwayat'])
            ->name('setoran.riwayat');

        Route::get('/api/sales-summary', function () {
            return response()->json([
                'total_setoran' => Setoran::where('user_id', Auth::id())->whereDate('created_at', today())->where('status', 'disetujui')->sum('nominal'),
                'toko_dikunjungi' => Toko::where('sales_id', Auth::id())->whereDate('updated_at', today())->count(),
            ]);
        })->name('api.sales.summary');
    });