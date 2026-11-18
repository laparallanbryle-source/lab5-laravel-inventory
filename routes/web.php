<?php

// Kinukuha ang mga controller na gagamitin sa mga route sa ibaba.
use App\Http\Controllers\DashboardController; // dashboard na may statistics
use App\Http\Controllers\ProductController;   // CRUD, filter, export, at stock in/out ng products
use App\Http\Controllers\ProfileController;   // galing sa Breeze, para sa profile ng user
use App\Http\Controllers\ReportController;    // inventory reports at CSV export
// Ang Route facade, dito natin dinedefine ang mga URL ng application.
use Illuminate\Support\Facades\Route;

// HOME PAGE ("/"): ililipat ang user sa login page.
Route::get('/', function () {
    return redirect()->route('login');
});

// DASHBOARD: GET /dashboard, tinatawag ang index() ng DashboardController. Kailangang naka-login (auth).
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// GROUP: lahat ng route sa loob nito ay protektado ng "auth" middleware (kailangan ng login).
Route::middleware('auth')->group(function () {
    // DASHBOARD STATS (JSON): ito ang kinukuha ng JavaScript tuwing 15 segundo para sa real-time update.
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    // PROFILE (galing sa Breeze).
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // EXPORT CSV ng products. DAPAT NASA ITAAS ito ng Route::resource, kung hindi, "export" ang ituturing na {product}.
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    // RESOURCE ROUTE: isang linya na gumagawa ng 7 route para sa products (index, create, store, show, edit, update, destroy).
    Route::resource('products', ProductController::class);
    // STOCK IN/OUT: POST /products/{id}/stock.
    Route::post('/products/{product}/stock', [ProductController::class, 'adjustStock'])->name('products.stock');

    // REPORTS: report page at CSV download.
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

// Mga route ng authentication ng Breeze (login, register, logout, forgot password, atbp.).
require __DIR__.'/auth.php';