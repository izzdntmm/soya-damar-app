<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\DeliveryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');
});


require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'role:sales'])->group(function () {
    Route::get('/sales/dashboard', function () {
        return view('sales.dashboard');
    })->name('sales.dashboard');
});

Route::middleware(['auth', 'role:sales'])->group(function () {
    Route::resource('stores', StoreController::class);
});

Route::middleware(['auth', 'role:sales'])->group(function () {
    Route::resource('deliveries', DeliveryController::class)->except(['show']);
});

// Untuk sales mengirimkan semua setoran
Route::post('/deliveries/submit', [App\Http\Controllers\DeliveryController::class, 'submit'])->name('deliveries.submit');

 // Untuk admin melihat laporan semua setoran yang sudah dikirim
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/reports/monthly', [App\Http\Controllers\Admin\ReportController::class, 'monthly'])->name('admin.reports.monthly');
    Route::get('/admin/laporan-bulanan/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportMonthlyPDF'])->name('admin.reports.monthly.pdf');
    Route::get('/admin/reports/{user}', [App\Http\Controllers\Admin\ReportController::class, 'show'])->name('admin.reports.show');
});

use NotificationChannels\WebPush\PushSubscription;

Route::post('/push/subscribe', function (\Illuminate\Http\Request $request) {
    $user = auth()->user();

    $user->updatePushSubscription(
        $request->endpoint,
        $request->keys['p256dh'],
        $request->keys['auth']
    );

    return response()->json(['success' => true]);
});




