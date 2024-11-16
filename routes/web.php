<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\LowStockController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::post('/custom-login', [CustomAuthController::class, 'login'])->name('custom-login');

Route::group(['middleware' => ['auth','audit.log']], function () {
    Route::get('/', [RouteController::class, 'dashboard'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('sales', SaleController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/dashboard/chart-data', [RouteController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/stock_history', [RouteController::class, 'stockHistory'])->name('products.stock-history');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/low-stock', [LowStockController::class, 'index'])->name('low-stock.index');
    Route::get('/low-stock/export/{type}', [LowStockController::class, 'export'])->name('low-stock.export');
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::put('/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('toggleActive');
    });
    // Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('audit-logs/export/{type}', [AuditLogController::class, 'export'])->name('audit-logs.export');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
