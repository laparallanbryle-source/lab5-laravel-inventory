<?php

// Kinukuha ang mga controller na gagamitin sa mga route sa ibaba.
use App\Http\Controllers\DashboardController; // para sa dashboard na may statistics
use App\Http\Controllers\ProductController;   // para sa CRUD at stock in/out ng products
use App\Http\Controllers\ProfileController;   // galing sa Breeze, para sa profile ng user
use App\Http\Controllers\ReportController;    // para sa inventory reports at CSV export
// Ang Route facade, dito natin dinedefine ang mga URL ng application.
use Illuminate\Support\Facades\Route;

// HOME PAGE ("/"): kapag binuksan ang pinaka-unang URL...
Route::get('/', function () {
    // ...ililipat ang user sa login page (route na may pangalang "login" galing sa Breeze).
    return redirect()->route('login');
});

// DASHBOARD: GET /dashboard, tinatawag ang index() method ng DashboardController.
Route::get('/dashboard', [DashboardController::class, 'index'])
    // Middleware "auth": kailangang naka-login. Kung hindi, ililipat sa login page.
    ->middleware('auth')
    // Pangalan ng route. Ginagamit sa route('dashboard') sa mga view para hindi hardcoded ang URL.
    ->name('dashboard');

// GROUP: lahat ng route sa loob nito ay protektado ng "auth" middleware (kailangan ng login).
Route::middleware('auth')->group(function () {
    // PROFILE (galing sa Breeze): ipakita ang edit profile page.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // PROFILE: i-save ang pagbabago sa profile (PATCH).
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // PROFILE: burahin ang account (DELETE).
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // RESOURCE ROUTE: isang linya na gumagawa ng 7 route para sa products (index, create, store, show, edit, update, destroy).
    Route::resource('products', ProductController::class);
    // STOCK IN/OUT: POST /products/{id}/stock, tinatawag ang adjustStock() method. Ang {product} ay ang id ng produkto.
    Route::post('/products/{product}/stock', [ProductController::class, 'adjustStock'])->name('products.stock');

    // REPORTS: ipakita ang inventory report page.
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    // REPORTS: i-download ang inventory bilang CSV file.
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

// Kinukuha ang mga route ng authentication ng Breeze (login, register, logout, forgot password, atbp.) mula sa routes/auth.php.
require __DIR__.'/auth.php';