<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ABKController;
use App\Http\Controllers\KapalController;
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
//Route::middleware(['guest:admin'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
//});

// Authenticated routes (only accessible when logged in)
// Route::middleware(['auth:admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // ABK routes - PERBAIKAN PENTING: Urutkan berdasarkan spesifisitas
    Route::prefix('abk')->name('abk.')->group(function () {
        // Export routes - DILETAKKAN SEBELUM ROUTE DENGAN PARAMETER
        Route::get('/export', [ABKController::class, 'export'])->name('export');
        Route::post('/export/excel', [ABKController::class, 'exportExcel'])->name('export.excel');
        Route::post('/export/pdf', [ABKController::class, 'exportPdf'])->name('export.pdf');
        
        // Mutasi routes - DILETAKKAN SEBELUM ROUTE DENGAN PARAMETER
        Route::prefix('mutasi')->name('mutasi.')->group(function () {
            Route::get('/', [ABKController::class, 'mutasiIndex'])->name('index');
            Route::get('/create', [ABKController::class, 'mutasiCreate'])->name('create');
            Route::post('/', [ABKController::class, 'mutasiStore'])->name('store');
            Route::get('/{id}', [ABKController::class, 'mutasiShow'])->name('show');
            Route::get('/{id}/edit', [ABKController::class, 'mutasiEdit'])->name('edit');
            Route::put('/{id}', [ABKController::class, 'mutasiUpdate'])->name('update');
            Route::delete('/{id}', [ABKController::class, 'mutasiDestroy'])->name('destroy');
        });
        
        // AJAX routes - DILETAKKAN SEBELUM ROUTE DENGAN PARAMETER
        Route::get('/ajax/kapal', [ABKController::class, 'getKapalList'])->name('ajax.kapal');
        Route::get('/ajax/jabatan', [ABKController::class, 'getJabatanList'])->name('ajax.jabatan');
        Route::post('/ajax/check-nrp', [ABKController::class, 'checkNrp'])->name('ajax.check-nrp');
        Route::get('/ajax/abk-by-kapal', [ABKController::class, 'getAbkByKapal'])->name('ajax.abk-by-kapal');
        Route::get('/kapal/{id_kapal}/abk', [ABKController::class, 'showByKapal'])->name('by-kapal');
        
        // Basic CRUD - DILETAKKAN DI BAWAH ROUTE YANG LEBIH SPESIFIK
        Route::get('/', [ABKController::class, 'index'])->name('index');
        Route::get('/create', [ABKController::class, 'create'])->name('create');
        Route::post('/', [ABKController::class, 'store'])->name('store');
        Route::get('/{id}', [ABKController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ABKController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ABKController::class, 'update'])->name('update');
        Route::delete('/{id}', [ABKController::class, 'destroy'])->name('destroy');
    });
    
    // Kapal routes
    Route::prefix('kapal')->name('kapal.')->group(function () {
        Route::get('/', [KapalController::class, 'index'])->name('index');
        Route::get('/create', [KapalController::class, 'create'])->name('create');
        Route::post('/', [KapalController::class, 'store'])->name('store');
        Route::get('/{id}', [KapalController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [KapalController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KapalController::class, 'update'])->name('update');
        Route::delete('/{id}', [KapalController::class, 'destroy'])->name('destroy');
    });
    
    // Monitoring routes
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/', [MonitoringController::class, 'index'])->name('index');
        Route::get('/sertijab', [MonitoringController::class, 'sertijab'])->name('sertijab');
        Route::get('/sertijab/detail/{id}', [MonitoringController::class, 'sertijabDetail'])->name('sertijab.detail');
        Route::get('/sertijab/export', [MonitoringController::class, 'exportSertijab'])->name('sertijab.export');
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
        Route::get('/laporan/index', [ArsipController::class, 'laporanIndex'])->name('laporan');
        Route::get('/laporan/generate', [ArsipController::class, 'generateLaporan'])->name('laporan.generate');
    });
    
    // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/update', [SettingsController::class, 'update'])->name('update');
    });
    
    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });
// });
