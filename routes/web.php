<?php

use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\LowStockController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

// Route::post('/custom-login', [CustomAuthController::class, 'login'])->name('custom-login');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [RouteController::class, 'dashboard'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('sales', SaleController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/dashboard/chart-data', [RouteController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/stock_history', [RouteController::class, 'stockHistory'])->name('products.stock-history');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/low-stock', [LowStockController::class, 'index'])->name('low-stock.index');
    Route::get('/low-stock/export/{type}', [LowStockController::class, 'export'])->name('low-stock.export');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
