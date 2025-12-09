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
use App\Http\Controllers\HistoryEntryController;
use App\Http\Controllers\PredictedGradeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudyController;
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

    Route::post('/study', [StudyController::class, 'start'])->name('study.start');
    Route::get('/study', [StudyController::class, 'show'])->name('study.show');
    Route::get('/study/status', [StudyController::class, 'status'])->name('study.status');
    Route::get('/study/queue', [StudyController::class, 'queue'])->name('study.queue');
    Route::post('/study/history/touch', [StudyController::class, 'touchHistory'])->name('study.history.touch');

    Route::get('/profile', ProfileController::class)->name('profile');

    Route::post('/profile/predicted-grades', [PredictedGradeController::class, 'store'])
        ->name('profile.predicted-grades.store');

    Route::post('/profile/history', [HistoryEntryController::class, 'store'])                   ->name('profile.history.store');
    Route::put('/profile/history/{historyEntry}', [HistoryEntryController::class, 'update'])    ->name('profile.history.update');
    Route::delete('/profile/history/{historyEntry}', [HistoryEntryController::class, 'destroy'])->name('profile.history.destroy');
});

Route::match(['get', 'post'], '/logout', [LoginController::class, 'destroy'])->name('logout');
