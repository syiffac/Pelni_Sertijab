<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\Kapal;
use App\Models\Sertijab; // Gunakan model lama
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PUKController extends Controller
{
    /**
     * Tampilkan dashboard PUK
     */
    public function dashboard()
    {
        return view('puk.dashboard');
    }

    /**
     * Tampilkan form upload dokumen PUK
     */
    public function uploadForm()
    {
        $kapals = Kapal::select('id', 'nama_kapal', 'home_base')
            ->orderBy('nama_kapal')
            ->get();
            
        return view('puk.upload-form', compact('kapals'));
    }

    /**
     * Ambil data mutasi berdasarkan kapal yang dipilih
     * PERBAIKAN: Hindari filter yang terlalu ketat
     */
    public function getMutasiByKapal(Request $request)
    {
        $request->validate([
            'id_kapal' => 'required|exists:kapal,id'
        ]);

        try {
            $kapal = Kapal::findOrFail($request->id_kapal);
            
            // DEBUGGING: Log total sertijab di tabel untuk kapal ini
            $totalSertijabInKapal = DB::table('sertijab_new')
                ->join('mutasi_new', 'sertijab_new.id_mutasi', '=', 'mutasi_new.id')
                ->where('mutasi_new.id_kapal', $request->id_kapal)
                ->count();
            
            Log::info('Total sertijab untuk kapal ID ' . $request->id_kapal . ': ' . $totalSertijabInKapal);
            
            // PERBAIKAN: Ambil semua sertijab untuk kapal tersebut tanpa filter status final
            $sertijabs = Sertijab::join('mutasi_new', 'sertijab_new.id_mutasi', '=', 'mutasi_new.id')
                ->where('mutasi_new.id_kapal', $request->id_kapal)
                ->whereIn('mutasi_new.status_mutasi', ['Disetujui', 'Selesai', 'Draft']) // Tambahkan Draft juga
                ->where('mutasi_new.perlu_sertijab', true)
                ->select('sertijab_new.*')
                ->get();
            
            Log::info('Sertijab ditemukan setelah filter: ' . $sertijabs->count());
            
            // Tambahkan mutasi yang belum memiliki sertijab sama sekali
            $mutasisTanpaSertijab = Mutasi::where('id_kapal', $request->id_kapal)
                ->whereIn('status_mutasi', ['Disetujui', 'Selesai', 'Draft']) // Tambahkan Draft juga
                ->where('perlu_sertijab', true)
                ->whereNotIn('id', $sertijabs->pluck('id_mutasi')->toArray())
                ->get();
            
            Log::info('Mutasi tanpa sertijab: ' . $mutasisTanpaSertijab->count());
            
            // Ambil detail mutasi untuk setiap sertijab
            $mutasis = [];
            
            // Gabungkan data dari sertijab yang sudah ada
            foreach ($sertijabs as $sertijab) {
                $mutasi = Mutasi::with([
                    'abkNaik',
                    'abkTurun', 
                    'jabatanTetapNaik',
                    'jabatanTetapTurun',
                    'jabatanMutasi',
                    'jabatanMutasiTurun'
                ])->find($sertijab->id_mutasi);
                
                if ($mutasi) {
                    // Tambahkan informasi sertijab ke mutasi
                    $mutasi->sertijab = $sertijab;
                    $mutasis[] = $mutasi;
                }
            }
            
            // Tambahkan mutasi yang belum memiliki sertijab
            foreach ($mutasisTanpaSertijab as $mutasi) {
                $mutasi->load([
                    'abkNaik',
                    'abkTurun', 
                    'jabatanTetapNaik',
                    'jabatanTetapTurun',
                    'jabatanMutasi',
                    'jabatanMutasiTurun'
                ]);
                
                // Set sertijab ke null untuk mutasi yang belum memiliki sertijab
                $mutasi->sertijab = null;
                $mutasis[] = $mutasi;
            }

            // Debug: Log untuk cek data
            Log::info('Total mutasis setelah gabungan: ' . count($mutasis));

            return response()->json([
                'success' => true,
                'kapal' => [
                    'id' => $kapal->id,
                    'nama_kapal' => $kapal->nama_kapal,
                    'home_base' => $kapal->home_base ?? '-'
                ],
                'mutasis' => collect($mutasis)->map(function($mutasi) {
                    // Cek apakah sudah ada dokumen sertijab
                    $dokumenInfo = $this->getDokumenInfo($mutasi);

                    return [
                        'id' => $mutasi->id,
                        'TMT' => $mutasi->TMT ? $mutasi->TMT->format('Y-m-d') : null,
                        'TAT' => $mutasi->TAT ? $mutasi->TAT->format('Y-m-d') : null,
                        'periode' => $mutasi->TMT && $mutasi->TAT ? 
                            $mutasi->TMT->format('d/m/Y') . ' - ' . $mutasi->TAT->format('d/m/Y') : '-',
                        'status_mutasi' => $mutasi->status_mutasi,
                        'jenis_mutasi' => $mutasi->jenis_mutasi,
                        'catatan' => $mutasi->catatan,
                        
                        // Data ABK Naik
                        'abk_naik' => [
                            'id' => $mutasi->id_abk_naik,
                            'nama' => $mutasi->nama_lengkap_naik,
                            'jabatan_tetap' => $mutasi->jabatanTetapNaik->nama_jabatan ?? '-',
                            'jabatan_mutasi' => $mutasi->jabatanMutasi->nama_jabatan ?? '-'
                        ],
                        
                        // Data ABK Turun (jika ada)
                        'abk_turun' => $mutasi->ada_abk_turun ? [
                            'id' => $mutasi->id_abk_turun,
                            'nama' => $mutasi->nama_lengkap_turun,
                            'jabatan_tetap' => $mutasi->jabatanTetapTurun->nama_jabatan ?? '-',
                            'jabatan_mutasi' => $mutasi->jabatanMutasiTurun->nama_jabatan ?? '-'
                        ] : null,
                        
                        // Status dokumen dari sertijab
                        'dokumen' => $dokumenInfo['dokumen'],
                        'dokumen_urls' => $dokumenInfo['dokumen_urls'],
                        'submitted_by_puk' => $mutasi->sertijab && $mutasi->sertijab->submitted_at ? true : false
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting mutasi by kapal: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mutasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload dokumen untuk mutasi tertentu ke sertijab_new
     */
    public function uploadDokumen(Request $request)
    {
        $request->validate([
            'id_mutasi' => 'required|exists:mutasi_new,id',
            'jenis_dokumen' => 'required|in:sertijab,familisasi,lampiran',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $mutasi = Mutasi::findOrFail($request->id_mutasi);
            $jenisDokumen = $request->jenis_dokumen;
            $file = $request->file('file');
            $pathColumn = "dokumen_{$jenisDokumen}_path";
            $statusColumn = "status_{$jenisDokumen}";

            // Temukan atau buat record sertijab
            $sertijab = Sertijab::firstOrNew(['id_mutasi' => $mutasi->id]);
            
            // Hapus file lama jika ada
            if ($sertijab->$pathColumn) {
                Storage::disk('public')->delete($sertijab->$pathColumn);
            }

            // Generate nama file yang unique
            $timestamp = now()->format('YmdHis');
            $filename = "{$jenisDokumen}_{$mutasi->id}_{$timestamp}." . $file->getClientOriginalExtension();
            
            // Simpan file ke storage
            $path = $file->storeAs("dokumen/{$jenisDokumen}", $filename, 'public');

            // Update atau set field sertijab
            $sertijab->$pathColumn = $path;
            $sertijab->$statusColumn = 'draft'; // Default status saat upload

            // Set informasi dasar jika baru dibuat
            if (!$sertijab->exists) {
                $sertijab->id_mutasi = $mutasi->id;
                $sertijab->status_dokumen = 'draft';
                $sertijab->updated_at = now();
                $sertijab->created_at = now();
            }

            $sertijab->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ucfirst($jenisDokumen) . ' berhasil diunggah',
                'file_info' => [
                    'name' => $file->getClientOriginalName(),
                    'size' => round($file->getSize() / 1024 / 1024, 2), // MB
                    'url' => Storage::url($path),
                    'type' => $jenisDokumen
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error uploading dokumen: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus dokumen dari sertijab_new
     */
    public function deleteDokumen(Request $request)
    {
        $request->validate([
            'id_mutasi' => 'required|exists:mutasi_new,id',
            'jenis_dokumen' => 'required|in:sertijab,familisasi,lampiran'
        ]);

        try {
            DB::beginTransaction();

            $jenisDokumen = $request->jenis_dokumen;
            $pathColumn = "dokumen_{$jenisDokumen}_path";
            $statusColumn = "status_{$jenisDokumen}";

            // Cari record sertijab
            $sertijab = Sertijab::where('id_mutasi', $request->id_mutasi)->first();

            if (!$sertijab) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen tidak ditemukan'
                ], 404);
            }

            // Hapus file dari storage
            if ($sertijab->$pathColumn) {
                Storage::disk('public')->delete($sertijab->$pathColumn);
                
                // Update database
                $sertijab->$pathColumn = null;
                $sertijab->$statusColumn = 'draft';
                $sertijab->save();
                
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ucfirst($jenisDokumen) . ' berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting dokumen: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit dokumen tunggal
     */
    public function submitDokumen(Request $request)
{
    $request->validate([
        'mutasi_id' => 'required|exists:mutasi_new,id'
    ]);

    try {
        DB::beginTransaction();

        $mutasi = Mutasi::findOrFail($request->mutasi_id);

        // Cari record sertijab
        $sertijab = Sertijab::where('id_mutasi', $mutasi->id)->first();
        
        if (!$sertijab) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada dokumen untuk disubmit'
            ], 400);
        }

        // Cek minimal sertijab dan familisasi harus ada
        if (empty($sertijab->dokumen_sertijab_path) || empty($sertijab->dokumen_familisasi_path)) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen Serah Terima Jabatan dan Familisasi wajib diupload sebelum submit.'
            ], 400);
        }

        // Update status dokumen - PERBAIKAN: tambahkan submitted_at
        $sertijab->submitted_at = now();
        $sertijab->status_dokumen = 'draft';
        $sertijab->save();

        DB::commit();

        // Buat notifikasi untuk admin jika ada
        if (class_exists('App\Services\NotificationService')) {
            try {
                NotificationService::createSubmitNotification($mutasi);
                NotificationService::createUnverifiedNotification($mutasi);
            } catch (\Exception $e) {
                Log::error("Error creating notification: " . $e->getMessage());
                // Lanjutkan proses meskipun notifikasi gagal
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil disubmit'
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error("Error submitting document: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal submit dokumen: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Batch submit dokumen
 */
public function batchSubmitDokumen(Request $request)
{
    $request->validate([
        'mutasi_ids' => 'required|array',
        'mutasi_ids.*' => 'exists:mutasi_new,id'
    ]);

    try {
        DB::beginTransaction();
        
        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        $mutasiList = [];
        
        foreach ($request->mutasi_ids as $mutasiId) {
            try {
                // Cari record sertijab
                $sertijab = Sertijab::where('id_mutasi', $mutasiId)->first();
                
                if (!$sertijab) {
                    $errors[] = "Mutasi ID {$mutasiId}: Tidak ada dokumen untuk disubmit.";
                    $failedCount++;
                    continue;
                }

                // Cek kelengkapan dokumen
                if (empty($sertijab->dokumen_sertijab_path) || empty($sertijab->dokumen_familisasi_path)) {
                    $errors[] = "Mutasi ID {$mutasiId}: Dokumen Sertijab dan Familisasi wajib ada.";
                    $failedCount++;
                    continue;
                }

                // Update status dokumen - PERBAIKAN: tambahkan submitted_at
                $sertijab->submitted_at = now();
                $sertijab->status_dokumen = 'draft';
                $sertijab->save();

                // Ambil data mutasi untuk notifikasi
                $mutasi = Mutasi::find($mutasiId);
                if ($mutasi) {
                    $mutasiList[] = $mutasi;
                }

                $successCount++;

            } catch (\Exception $e) {
                $errors[] = "Mutasi ID {$mutasiId}: " . $e->getMessage();
                $failedCount++;
                Log::error("Batch submit error for mutasi {$mutasiId}: " . $e->getMessage());
            }
        }
        
        DB::commit();
        
        // Buat notifikasi untuk dokumen yang berhasil disubmit
        foreach ($mutasiList as $mutasi) {
            try {
                if (class_exists('App\Services\NotificationService')) {
                    NotificationService::createUnverifiedNotification($mutasi);
                }
            } catch (\Exception $e) {
                Log::error("Error creating notification for mutasi {$mutasi->id}: " . $e->getMessage());
            }
        }
        
        // Siapkan response message
        $message = "";
        if ($successCount > 0) {
            $message .= "{$successCount} dokumen berhasil disubmit";
        }
        if ($failedCount > 0) {
            $message .= ($successCount > 0 ? ", {$failedCount} gagal" : "{$failedCount} dokumen gagal disubmit");
        }
        
        return response()->json([
            'success' => $successCount > 0,
            'message' => $message,
            'details' => [
                'success_count' => $successCount,
                'failed_count' => $failedCount,
                'errors' => $errors
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error("Batch submit error: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal submit dokumen: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Create or update sertijab record when PUK submits documents
     */
    private function createOrUpdateSertijabRecord(Mutasi $mutasi)
    {
        $sertijab = Sertijab::updateOrCreate(
            ['id_mutasi' => $mutasi->id],
            [
                'dokumen_sertijab_path' => $mutasi->dokumen_sertijab,
                'dokumen_familisasi_path' => $mutasi->dokumen_familisasi,
                'dokumen_lampiran_path' => $mutasi->dokumen_lampiran,
                'status_sertijab' => 'draft',
                'status_familisasi' => 'draft',
                'status_lampiran' => $mutasi->dokumen_lampiran ? 'draft' : null,
                'status_dokumen' => 'draft',
                'submitted_at' => now(),
                'catatan_admin' => null, // Reset admin comment when resubmitted
            ]
        );

        Log::info("Sertijab record created/updated for mutasi ID {$mutasi->id}", [
            'sertijab_id' => $sertijab->id,
            'mutasi_id' => $mutasi->id,
            'abk_naik' => $mutasi->nama_lengkap_naik,
            'abk_turun' => $mutasi->nama_lengkap_turun
        ]);
    }

    private function getDokumenInfo($mutasi)
{
    // Default values
    $dokumen = [
        'sertijab' => false,
        'familisasi' => false,
        'lampiran' => false
    ];
    
    $dokumen_urls = [
        'sertijab' => null,
        'familisasi' => null,
        'lampiran' => null
    ];
    
    // Cek apakah mutasi sudah memiliki relasi sertijab
    if ($mutasi->sertijab) {
        // Ambil dokumen dari relasi sertijab
        $dokumen['sertijab'] = !empty($mutasi->sertijab->dokumen_sertijab_path);
        $dokumen['familisasi'] = !empty($mutasi->sertijab->dokumen_familisasi_path);
        $dokumen['lampiran'] = !empty($mutasi->sertijab->dokumen_lampiran_path);
        
        // Ambil URL dari relasi sertijab
        $dokumen_urls['sertijab'] = $dokumen['sertijab'] ? 
            Storage::url($mutasi->sertijab->dokumen_sertijab_path) : null;
            
        $dokumen_urls['familisasi'] = $dokumen['familisasi'] ? 
            Storage::url($mutasi->sertijab->dokumen_familisasi_path) : null;
            
        $dokumen_urls['lampiran'] = $dokumen['lampiran'] ? 
            Storage::url($mutasi->sertijab->dokumen_lampiran_path) : null;
    }
    
    return [
        'dokumen' => $dokumen,
        'dokumen_urls' => $dokumen_urls
    ];
}
}