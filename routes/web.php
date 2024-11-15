<?php

use App\Http\Controllers\Auth\CustomAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RouteController;
use Illuminate\Support\Facades\Route;

Route::post('/custom-login', [CustomAuthController::class, 'login'])->name('custom-login');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [RouteController::class, 'dashboard'])->name('dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
