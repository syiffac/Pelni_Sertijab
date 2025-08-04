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
use App\Http\Controllers\RoleSelectionController;
use App\Http\Controllers\PUKController;
use App\Http\Controllers\MutasiController;


// Root redirect - arahkan ke role selection
Route::get('/', [RoleSelectionController::class, 'index'])->name('role.selection');


// Role selection routes
Route::get('/select-role', [RoleSelectionController::class, 'index'])->name('role.selection');
Route::post('/select-role', [RoleSelectionController::class, 'selectRole'])->name('role.select');


// Guest routes (hanya bisa diakses jika belum login)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');


// PUK routes
Route::prefix('puk')->name('puk.')->group(function () {
    Route::get('/', [PUKController::class, 'dashboard'])->name('dashboard');
    Route::get('/upload-form', [PUKController::class, 'uploadForm'])->name('upload-form');
    Route::post('/get-mutasi-by-kapal', [PUKController::class, 'getMutasiByKapal'])->name('get-mutasi-by-kapal');
    Route::post('/upload-dokumen', [PUKController::class, 'uploadDokumen'])->name('upload-dokumen');
    Route::delete('/delete-dokumen', [PUKController::class, 'deleteDokumen'])->name('delete-dokumen');
    
    // Tambahan routes untuk submit
    Route::post('/submit-dokumen', [PUKController::class, 'submitDokumen'])->name('submit-dokumen');
    Route::post('/batch-submit-dokumen', [PUKController::class, 'batchSubmitDokumen'])->name('batch-submit-dokumen');
});

// Route untuk check NRP (tanpa middleware auth)
Route::post('/abk/check-nrp', [ABKController::class, 'checkNRP'])->name('abk.check-nrp');


// Protected admin routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/wak', [ABKController::class, 'index']);
    
    // ABK routes
    Route::prefix('abk')->name('abk.')->group(function () {
        // Export routes - DILETAKKAN SEBELUM ROUTE DENGAN PARAMETER
        Route::get('/export', [ABKController::class, 'export'])->name('export');
        Route::post('/export/excel', [ABKController::class, 'exportExcel'])->name('export.excel');
        Route::post('/export/pdf', [ABKController::class, 'exportPdf'])->name('export.pdf');
        
        // Template routes
        Route::get('/template/excel', [ABKController::class, 'downloadExcelTemplate'])->name('template.excel');
        Route::get('/template/pdf', [ABKController::class, 'downloadPdfTemplate'])->name('template.pdf');
        Route::post('/import', [ABKController::class, 'import'])->name('import');
        
        // AJAX routes - DILETAKKAN SEBELUM ROUTE DENGAN PARAMETER
        Route::get('/ajax/kapal', [ABKController::class, 'getKapalList'])->name('ajax.kapal');
        Route::get('/ajax/jabatan', [ABKController::class, 'getJabatanList'])->name('ajax.jabatan');
        Route::get('/ajax/abk-by-kapal', [ABKController::class, 'getAbkByKapal'])->name('ajax.abk-by-kapal');
        Route::get('/kapal/{id_kapal}/abk', [ABKController::class, 'showByKapal'])->name('by-kapal');
        
        // Check NRP route
        Route::get('/check-nrp', [ABKController::class, 'checkNRP'])->name('check-nrp');
        
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
    
    // MUTASI ROUTES - Pastikan urutan yang benar
    Route::prefix('mutasi')->name('mutasi.')->group(function () {
        // AJAX Routes untuk search ABK - HARUS DI ATAS ROUTES DENGAN PARAMETER
        Route::get('/search-abk', [MutasiController::class, 'searchAbk'])->name('search-abk');
        Route::get('/abk-detail/{id}', [MutasiController::class, 'getAbkDetail'])->name('abk-detail');
        Route::get('/ajax/abk-list', [MutasiController::class, 'getAbkList'])->name('ajax.abk-list');
        Route::get('/ajax/jabatan-list', [MutasiController::class, 'getJabatanList'])->name('ajax.jabatan-list');
        Route::get('/ajax/kapal-list', [MutasiController::class, 'getKapalList'])->name('ajax.kapal-list');
        Route::get('/export', [MutasiController::class, 'export'])->name('export');
        
        // Dokumen Routes
        Route::post('/{id}/upload-dokumen', [MutasiController::class, 'uploadDokumen'])->name('upload-dokumen');
        Route::delete('/{id}/delete-dokumen', [MutasiController::class, 'deleteDokumen'])->name('delete-dokumen');
        
        // Approval Routes
        Route::post('/{id}/approve', [MutasiController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [MutasiController::class, 'reject'])->name('reject');
        Route::post('/{id}/complete', [MutasiController::class, 'complete'])->name('complete');
        
        // Basic CRUD Routes - DI BAWAH ROUTES YANG LEBIH SPESIFIK
        Route::get('/', [MutasiController::class, 'index'])->name('index');
        Route::get('/create', [MutasiController::class, 'create'])->name('create');
        Route::post('/', [MutasiController::class, 'store'])->name('store');
        Route::get('/{id}', [MutasiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [MutasiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MutasiController::class, 'update'])->name('update');
        Route::delete('/{id}', [MutasiController::class, 'destroy'])->name('destroy');
    });
    
    // Monitoring routes - UPDATED dengan routes yang missing
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        // Dashboard monitoring
        Route::get('/', [MonitoringController::class, 'index'])->name('index');
        
        // Document verification - TAMBAHAN ROUTES YANG MISSING
        Route::get('/documents', [MonitoringController::class, 'documents'])->name('documents');
        Route::get('/documents/{id}', [MonitoringController::class, 'show'])->name('documents.show');
        Route::put('/documents/{id}/verify', [MonitoringController::class, 'updateVerification'])->name('documents.verify');
        Route::post('/documents/{id}/quick-verify', [MonitoringController::class, 'quickVerifyAll'])->name('documents.quick-verify');
        
        // Legacy routes (untuk backward compatibility)
        Route::get('/sertijab', [MonitoringController::class, 'sertijab'])->name('sertijab');
        Route::get('/sertijab/detail/{id}', [MonitoringController::class, 'sertijabDetail'])->name('sertijab.detail');
        Route::put('/sertijab/verify/{id}', [MonitoringController::class, 'verifySertijab'])->name('verify');
        Route::get('/sertijab/export', [MonitoringController::class, 'exportSertijab'])->name('sertijab.export');
    });
    
    // Arsip routes - UPDATE dengan routes yang missing
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('/', [ArsipController::class, 'index'])->name('index');
        Route::get('/search', [ArsipController::class, 'search'])->name('search');
        Route::get('/create', [ArsipController::class, 'create'])->name('create');
        Route::post('/', [ArsipController::class, 'store'])->name('store');
        Route::get('/{id}', [ArsipController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArsipController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArsipController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArsipController::class, 'destroy'])->name('destroy');
        
        // TAMBAHAN routes yang missing
        Route::get('/kapal/{kapalId}', [ArsipController::class, 'byKapal'])->name('by-kapal');
        Route::get('/export/excel', [ArsipController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [ArsipController::class, 'exportPdf'])->name('export.pdf');
        
        // Laporan routes
        Route::get('/laporan/index', [ArsipController::class, 'laporanIndex'])->name('laporan');
        Route::get('/laporan/generate', [ArsipController::class, 'generateLaporan'])->name('laporan.generate');
        Route::post('/bulk-update-status', [ArsipController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
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
});
