<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ABKController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;



// Root redirect
Route::get('/', function () {
    if (auth()->guard('admin')->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Guest routes (only accessible when not logged in)
Route::middleware(['guest:admin'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// Authenticated routes (only accessible when logged in)
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // ABK routes
    Route::prefix('abk')->name('abk.')->group(function () {
        Route::get('/', [ABKController::class, 'index'])->name('index');
        Route::get('/create', [ABKController::class, 'create'])->name('create');
        Route::post('/', [ABKController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ABKController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ABKController::class, 'update'])->name('update');
        Route::delete('/{id}', [ABKController::class, 'destroy'])->name('destroy');
        Route::get('/export', [ABKController::class, 'export'])->name('export');
        Route::get('/export/pdf', [ABKController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [ABKController::class, 'exportExcel'])->name('export.excel');
    });
    
    // Monitoring routes
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/', [MonitoringController::class, 'index'])->name('index');
        Route::get('/sertijab', [MonitoringController::class, 'sertijab'])->name('sertijab');
        Route::get('/sertijab/data', [MonitoringController::class, 'sertijabData'])->name('sertijab.data');
    });
    
    // Arsip routes
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('/', [ArsipController::class, 'index'])->name('index');
        Route::get('/search', [ArsipController::class, 'search'])->name('search');
        Route::get('/create', [ArsipController::class, 'create'])->name('create');
        Route::post('/', [ArsipController::class, 'store'])->name('store');
        Route::get('/{id}', [ArsipController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArsipController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArsipController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArsipController::class, 'destroy'])->name('destroy');
        Route::get('/laporan', [ArsipController::class, 'laporan'])->name('laporan');
        Route::get('/laporan/pdf', [ArsipController::class, 'laporanPdf'])->name('laporan.pdf');
        Route::get('/laporan/excel', [ArsipController::class, 'laporanExcel'])->name('laporan.excel');
    });
    
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
