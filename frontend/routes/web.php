<?php

/**
 * Web routes for Powered Learning, connecting incoming HTTP requests to the appropriate controllers.
 *
 * Inputs: requests entering through the web middleware stack.
 * Outputs: controller invocations rendering pages or mutating authentication state.
 */

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/auth/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/auth/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/profile', ProfileController::class)->name('profile');
});

Route::match(['get', 'post'], '/logout', [LoginController::class, 'destroy'])->name('logout');
