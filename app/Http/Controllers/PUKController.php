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
     */
    public function getMutasiByKapal(Request $request)
    {
        $request->validate([
            'id_kapal' => 'required|exists:kapal,id'
        ]);

        try {
            $kapal = Kapal::findOrFail($request->id_kapal);
            
            // PERBAIKAN: Ambil mutasi berdasarkan kapal tujuan yang statusnya disetujui atau selesai
            // DAN yang memerlukan dokumen upload
            $mutasis = Mutasi::with([
                    'abkNaik',
                    'abkTurun', 
                    'jabatanTetapNaik',
                    'jabatanTetapTurun',
                    'jabatanMutasi',
                    'jabatanMutasiTurun'
                ])
                ->where('id_kapal', $request->id_kapal)
                // UBAH: Tampilkan semua status untuk debugging
                ->whereIn('status_mutasi', ['Draft', 'Disetujui', 'Selesai'])
                // TAMBAHKAN: Filter yang perlu sertijab
                ->where('perlu_sertijab', true)
                ->orderBy('TMT', 'desc')
                ->get();

            // DEBUG: Log untuk cek data
            Log::info('Kapal ID: ' . $request->id_kapal);
            Log::info('Mutasi count: ' . $mutasis->count());
            Log::info('Mutasi data: ' . $mutasis->toJson());

            return response()->json([
                'success' => true,
                'kapal' => [
                    'id' => $kapal->id,
                    'nama_kapal' => $kapal->nama_kapal,
                    'home_base' => $kapal->home_base ?? '-'
                ],
                'mutasis' => $mutasis->map(function($mutasi) {
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
                        
                        // Status dokumen
                        'dokumen' => [
                            'sertijab' => !empty($mutasi->dokumen_sertijab),
                            'familisasi' => !empty($mutasi->dokumen_familisasi),
                            'lampiran' => !empty($mutasi->dokumen_lampiran),
                        ],
                        
                        // URL dokumen jika ada
                        'dokumen_urls' => [
                            'sertijab' => $mutasi->dokumen_sertijab ? Storage::url($mutasi->dokumen_sertijab) : null,
                            'familisasi' => $mutasi->dokumen_familisasi ? Storage::url($mutasi->dokumen_familisasi) : null,
                            'lampiran' => $mutasi->dokumen_lampiran ? Storage::url($mutasi->dokumen_lampiran) : null,
                        ]
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
     * Upload dokumen untuk mutasi tertentu
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

            // Hapus file lama jika ada
            $columnName = "dokumen_{$jenisDokumen}";
            if ($mutasi->$columnName) {
                Storage::disk('public')->delete($mutasi->$columnName);
            }

            // Generate nama file yang unique
            $timestamp = now()->format('YmdHis');
            $filename = "{$jenisDokumen}_{$mutasi->id}_{$timestamp}." . $file->getClientOriginalExtension();
            
            // Simpan file ke storage
            $path = $file->storeAs("dokumen/puk/{$jenisDokumen}", $filename, 'public');

            // Update database
            $mutasi->update([
                $columnName => $path
            ]);

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
     * Hapus dokumen
     */
    public function deleteDokumen(Request $request)
    {
        $request->validate([
            'id_mutasi' => 'required|exists:mutasi_new,id',
            'jenis_dokumen' => 'required|in:sertijab,familisasi,lampiran'
        ]);

        try {
            DB::beginTransaction();

            $mutasi = Mutasi::findOrFail($request->id_mutasi);
            $jenisDokumen = $request->jenis_dokumen;
            $columnName = "dokumen_{$jenisDokumen}";

            // Hapus file dari storage
            if ($mutasi->$columnName) {
                Storage::disk('public')->delete($mutasi->$columnName);
                
                // Update database
                $mutasi->update([
                    $columnName => null
                ]);
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
            $mutasi = Mutasi::findOrFail($request->mutasi_id);
            
            if (!$mutasi->dokumen_sertijab || !$mutasi->dokumen_familisasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen Sertijab dan Familisasi wajib diupload sebelum submit'
                ], 400);
            }
            
            $mutasi->update([
                'submitted_by_puk' => true,
                'submitted_at' => now()
            ]);

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
            $count = 0;
            $mutasiList = [];
            
            foreach ($request->mutasi_ids as $mutasiId) {
                $mutasi = Mutasi::find($mutasiId);
                
                if ($mutasi && $mutasi->dokumen_sertijab && $mutasi->dokumen_familisasi && !$mutasi->submitted_by_puk) {
                    $mutasi->update([
                        'submitted_by_puk' => true,
                        'submitted_at' => now()
                    ]);
                    
                    $count++;
                    $mutasiList[] = $mutasi;
                    
                    // Buat notifikasi untuk admin
                    NotificationService::createSubmitNotification($mutasi);
                }
            }
            
            // Buat notifikasi untuk dokumen yang belum diverifikasi
            foreach ($mutasiList as $mutasi) {
                NotificationService::createUnverifiedNotification($mutasi);
            }
            
            return response()->json([
                'success' => true,
                'message' => $count . ' dokumen berhasil disubmit'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal submit dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
}
