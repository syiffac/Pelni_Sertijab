<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ABKController;
use App\Http\Controllers\KapalController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\RoleSelectionController;
use App\Http\Controllers\PUKController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\NotificationController;


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
    // Dashboard - PERBAIKAN: Hapus duplikasi dan gunakan satu route saja
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard real-time API
    Route::get('/dashboard/realtime-data', [DashboardController::class, 'getRealtimeData'])->name('dashboard.realtime');

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
        Route::get('/export/page', [ABKController::class, 'exportPage'])->name('export.page');
        Route::get('/export/{format?}', [ABKController::class, 'export'])->name('export');
        Route::post('/import', [ABKController::class, 'import'])->name('import');
        
        // Riwayat Routes
        Route::get('/riwayat/{id}', [ABKController::class, 'viewRiwayatDetail'])->name('riwayat.detail');
        
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
        Route::get('/documents/{id}', [MonitoringController::class, 'show'])->name('show');
        Route::put('/documents/{id}/verify', [MonitoringController::class, 'updateVerification'])->name('documents.verify');
        Route::post('/documents/{id}/quick-verify', [MonitoringController::class, 'quickVerifyAll'])->name('documents.quick-verify');
        Route::put('/documents/{id}/update-note', [MonitoringController::class, 'updateNote'])->name('documents.update-note');

        // Legacy routes (untuk backward compatibility)
        Route::get('/sertijab', [MonitoringController::class, 'sertijab'])->name('sertijab');
        Route::get('/sertijab/detail/{id}', [MonitoringController::class, 'sertijabDetail'])->name('sertijab.detail');
        Route::put('/sertijab/verify/{id}', [MonitoringController::class, 'verifySertijab'])->name('verify');
        Route::get('/sertijab/export', [MonitoringController::class, 'exportSertijab'])->name('sertijab.export');
        
        // Detail route - TAMBAHAN ROUTE BARU
        Route::get('/detail/{id}', [MonitoringController::class, 'detail'])->name('detail');
    });
    
    // Arsip routes - UPDATE dengan routes yang missing
    Route::prefix('arsip')->name('arsip.')->group(function () {
        Route::get('/', [ArsipController::class, 'index'])->name('index');
        Route::get('/search', [ArsipController::class, 'search'])->name('search');
        Route::get('/create', [ArsipController::class, 'create'])->name('create');
        Route::get('/get-mutasi-by-kapal', [ArsipController::class, 'getMutasiByKapal'])->name('get-mutasi-by-kapal');
        Route::post('/store', [ArsipController::class, 'store'])->name('store');
        Route::get('/{id}', [ArsipController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ArsipController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArsipController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArsipController::class, 'destroy'])->name('destroy');
        
        // ADDED: Missing laporan route
        Route::get('/laporan/export', [ArsipController::class, 'generateLaporan'])->name('laporan');
        
        // Document verification routes
        Route::post('/{id}/verify-document', [ArsipController::class, 'verifyDocument'])->name('verify-document');
        Route::post('/{id}/verify-all-documents', [ArsipController::class, 'verifyAllDocuments'])->name('verify-all-documents');
        Route::post('/{id}/update-status', [ArsipController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk-update-status', [ArsipController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
    });
});

// Add this route for AJAX call
Route::get('/arsip/get-mutasi-by-kapal', [ArsipController::class, 'getMutasiByKapal'])->name('arsip.get-mutasi-by-kapal');

// ABK export-import routes
// Route::middleware(['auth'])->group(function () {
//     // ABK routes
//     Route::resource('abk', ABKController::class);
//     Route::get('abk/check-nrp', [ABKController::class, 'checkNRP'])->name('abk.check-nrp');
    
//     // Export & Import routes - ADD THESE
//     Route::get('abk-export-import', [ABKController::class, 'exportImport'])->name('abk.export-import');
//     Route::get('abk/export/{format?}', [ABKController::class, 'export'])->name('abk.export');
//     Route::post('abk/import', [ABKController::class, 'import'])->name('abk.import');
//     Route::get('abk/template/{type}', [ABKController::class, 'downloadTemplate'])->name('abk.template');
    
//     // Specific template routes
//     Route::get('abk/template/excel', [ABKController::class, 'downloadTemplate'])->defaults('type', 'excel')->name('abk.template.excel');
//     Route::get('abk/template/pdf', [ABKController::class, 'downloadTemplate'])->defaults('type', 'pdf')->name('abk.template.pdf');

//     // Riwayat Routes - TAMBAH INI
//     Route::get('/riwayat/history', [ABKController::class, 'getRiwayatHistory'])->name('riwayat.history');
// });

Route::middleware(['auth'])->prefix('abk')->name('abk.')->group(function () {
    // Export & Import Routes - HARUS DI ATAS ROUTE DENGAN PARAMETER
    Route::get('/export/page', [ABKController::class, 'exportPage'])->name('export.page');
    Route::get('/export/{format?}', [ABKController::class, 'export'])->name('export');
    Route::post('/import', [ABKController::class, 'import'])->name('import');
    
    // Template Routes
    Route::get('/template/excel', [ABKController::class, 'downloadTemplate'])->name('template.excel');
    
    // Riwayat Routes
    Route::get('/riwayat/history', [ABKController::class, 'getRiwayatHistory'])->name('riwayat.history');
    Route::get('/riwayat/{id}', [ABKController::class, 'viewRiwayatDetail'])->name('riwayat.detail');
    
    // AJAX routes
    Route::get('/ajax/kapal', [ABKController::class, 'getKapalList'])->name('ajax.kapal');
    Route::get('/ajax/jabatan', [ABKController::class, 'getJabatanList'])->name('ajax.jabatan');
    Route::get('/ajax/abk-by-kapal', [ABKController::class, 'getAbkByKapal'])->name('ajax.abk-by-kapal');
    Route::get('/kapal/{id_kapal}/abk', [ABKController::class, 'showByKapal'])->name('by-kapal');
    Route::get('/check-nrp', [ABKController::class, 'checkNRP'])->name('check-nrp');
    
    // Basic CRUD - DI BAWAH ROUTE YANG LEBIH SPESIFIK
    Route::get('/', [ABKController::class, 'index'])->name('index');
    Route::get('/create', [ABKController::class, 'create'])->name('create');
    Route::post('/', [ABKController::class, 'store'])->name('store');
    Route::get('/{id}', [ABKController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [ABKController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ABKController::class, 'update'])->name('update');
    Route::delete('/{id}', [ABKController::class, 'destroy'])->name('destroy');
});

// Notification routes - PERBAIKAN URUTAN ROUTE
Route::middleware(['auth'])->group(function() {
    // Route destroy-all HARUS SEBELUM route parameter {notification}
    Route::delete('/notifications/destroy-all', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    
    // Route lainnya
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // API endpoint
    Route::get('/api/notifications', [NotificationController::class, 'getNotifications'])->name('api.notifications');
});

// Route testing (hapus setelah selesai testing)
Route::get('/test-notification', function() {
    try {
        $notification = \App\Services\NotificationService::createTestNotification();
        return response()->json([
            'success' => true,
            'message' => 'Test notification created successfully',
            'notification' => $notification
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
})->middleware('auth');

// Pastikan route untuk mutasi menggunakan resource lengkap
Route::resource('mutasi', MutasiController::class);

// Atau jika ingin lebih spesifik, tambahkan route delete:
Route::delete('/mutasi/{id}', [MutasiController::class, 'destroy'])->name('mutasi.destroy');

// Tambahkan di web.php jika route monitoring.index belum ada
Route::middleware(['auth'])->prefix('monitoring')->name('monitoring.')->group(function () {
    Route::get('/', function() {
        // Redirect ke halaman yang sesuai, misalnya mutasi index
        return redirect()->route('mutasi.index');
    })->name('index');
    
    Route::get('/detail/{id}', [MonitoringController::class, 'detail'])->name('detail');
});
