<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kapal;
use App\Models\Mutasi;
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
     * Submit dokumen sertijab ke admin
     */
    public function submitDokumen(Request $request)
    {
        $request->validate([
            'id_mutasi' => 'required|exists:mutasi_new,id'
        ]);

        try {
            DB::beginTransaction();

            $mutasi = Mutasi::findOrFail($request->id_mutasi);

            // Cek apakah mutasi sudah pernah disubmit
            $existingSertijab = Sertijab::where('id_mutasi', $mutasi->id)->first();
            if ($existingSertijab) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mutasi ini sudah pernah disubmit ke admin.'
                ], 400);
            }

            // Cek minimal sertijab dan familisasi harus ada
            if (empty($mutasi->dokumen_sertijab) || empty($mutasi->dokumen_familisasi)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen Serah Terima Jabatan dan Familisasi wajib diupload sebelum submit.'
                ], 400);
            }

            // FIXED: Handle status_lampiran dengan benar
            $statusLampiran = null;
            if (!empty($mutasi->dokumen_lampiran)) {
                $statusLampiran = 'draft'; // Set status default jika ada dokumen lampiran
            }

            // Buat record sertijab baru dengan data yang benar
            $sertijabData = [
                'id_mutasi' => $mutasi->id,
                'dokumen_sertijab_path' => $mutasi->dokumen_sertijab,
                'dokumen_familisasi_path' => $mutasi->dokumen_familisasi,
                'dokumen_lampiran_path' => $mutasi->dokumen_lampiran, // Bisa null
                'status_sertijab' => 'draft',
                'status_familisasi' => 'draft',
                'status_lampiran' => $statusLampiran, // FIXED: Set ke null atau 'draft'
                'status_dokumen' => 'draft',
                'submitted_at' => now(),
                'catatan_admin' => null
            ];

            // Debug log untuk troubleshooting
            Log::info('Creating sertijab with data:', $sertijabData);

            $sertijab = Sertijab::create($sertijabData);

            // Update flag submitted di mutasi
            $mutasi->update([
                'submitted_by_puk' => true,
                'submitted_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil disubmit ke admin untuk verifikasi.',
                'sertijab_id' => $sertijab->id
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error submitting dokumen: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal submit dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batch submit multiple dokumen sertijab
     */
    public function batchSubmitDokumen(Request $request)
    {
        $request->validate([
            'mutasi_ids' => 'required|array|min:1',
            'mutasi_ids.*' => 'exists:mutasi_new,id'
        ]);

        try {
            DB::beginTransaction();

            $successCount = 0;
            $failedCount = 0;
            $errors = [];

            foreach ($request->mutasi_ids as $mutasiId) {
                try {
                    $mutasi = Mutasi::findOrFail($mutasiId);

                    // Cek apakah sudah disubmit
                    $existingSertijab = Sertijab::where('id_mutasi', $mutasi->id)->first();
                    if ($existingSertijab) {
                        $errors[] = "Mutasi ID {$mutasiId} sudah pernah disubmit.";
                        $failedCount++;
                        continue;
                    }

                    // Cek kelengkapan dokumen
                    if (empty($mutasi->dokumen_sertijab) || empty($mutasi->dokumen_familisasi)) {
                        $errors[] = "Mutasi ID {$mutasiId}: Dokumen Sertijab dan Familisasi wajib ada.";
                        $failedCount++;
                        continue;
                    }

                    // FIXED: Handle status_lampiran dengan benar
                    $statusLampiran = null;
                    if (!empty($mutasi->dokumen_lampiran)) {
                        $statusLampiran = 'draft';
                    }

                    // Create sertijab record
                    $sertijabData = [
                        'id_mutasi' => $mutasi->id,
                        'dokumen_sertijab_path' => $mutasi->dokumen_sertijab,
                        'dokumen_familisasi_path' => $mutasi->dokumen_familisasi,
                        'dokumen_lampiran_path' => $mutasi->dokumen_lampiran,
                        'status_sertijab' => 'draft',
                        'status_familisasi' => 'draft',
                        'status_lampiran' => $statusLampiran, // FIXED: Null atau 'draft'
                        'status_dokumen' => 'draft',
                        'submitted_at' => now(),
                        'catatan_admin' => null
                    ];

                    Sertijab::create($sertijabData);

                    // Update mutasi flag
                    $mutasi->update([
                        'submitted_by_puk' => true,
                        'submitted_at' => now()
                    ]);

                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = "Mutasi ID {$mutasiId}: " . $e->getMessage();
                    $failedCount++;
                    Log::error("Batch submit error for mutasi {$mutasiId}: " . $e->getMessage());
                }
            }

            DB::commit();

            $message = "Batch submit selesai. Berhasil: {$successCount}, Gagal: {$failedCount}";
            
            if (!empty($errors)) {
                $message .= "\n\nDetail error:\n" . implode("\n", $errors);
            }

            return response()->json([
                'success' => $successCount > 0,
                'message' => $message,
                'summary' => [
                    'success_count' => $successCount,
                    'failed_count' => $failedCount,
                    'errors' => $errors
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in batch submit: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal batch submit dokumen: ' . $e->getMessage()
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
}
