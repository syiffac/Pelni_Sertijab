<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sertijab;
use App\Models\Kapal;
use App\Models\Mutasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    /**
     * Display arsip index page
     */
    public function index()
    {
        // Get stats
        $stats = $this->getArsipStats();
        
        // Get kapal list with arsip counts
        $kapalList = Kapal::withCount([
            'mutasi as total_arsip' => function($query) {
                $query->whereHas('sertijab', function($q) {
                    $q->whereNotNull('submitted_at');
                });
            },
            'mutasi as final_arsip' => function($query) {
                $query->whereHas('sertijab', function($q) {
                    $q->where('status_dokumen', 'final');
                });
            },
            'mutasi as draft_arsip' => function($query) {
                $query->whereHas('sertijab', function($q) {
                    $q->whereIn('status_dokumen', ['draft', 'partial']);
                });
            }
        ])
        ->orderBy('nama_kapal')
        ->get();
        
        // Get monthly data for chart
        $monthlyData = $this->getMonthlyArsipData();
        
        return view('arsip.index', compact('stats', 'kapalList', 'monthlyData'));
    }
    
    /**
     * Display search page with filters
     */
    public function search(Request $request)
    {
        $kapalId = $request->input('kapal_id');
        $status = $request->input('status', 'all');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
        $searchTerm = $request->input('search');
        
        // Get kapal list for filter
        $kapalList = Kapal::orderBy('nama_kapal')->get();
        
        // Build query
        $query = Sertijab::with([
            'mutasi.kapal',
            'mutasi.abkNaik',
            'mutasi.abkTurun',
            'mutasi.jabatanTetapNaik',
            'mutasi.jabatanTetapTurun',
            'mutasi.jabatanMutasi',
            'adminVerifikator'
        ])->whereNotNull('submitted_at');
        
        // Apply filters
        if ($kapalId) {
            $query->whereHas('mutasi', function($q) use ($kapalId) {
                $q->where('id_kapal', $kapalId);
            });
        }
        
        if ($status !== 'all') {
            $query->where('status_dokumen', $status);
        }
        
        if ($bulan) {
            $query->whereMonth('submitted_at', $bulan);
        }
        
        if ($tahun) {
            $query->whereYear('submitted_at', $tahun);
        }
        
        if ($searchTerm) {
            $query->whereHas('mutasi', function($q) use ($searchTerm) {
                $q->where(function($subQ) use ($searchTerm) {
                    $subQ->where('nama_lengkap_naik', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('nama_lengkap_turun', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('id_abk_naik', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('id_abk_turun', 'LIKE', "%{$searchTerm}%");
                });
            });
        }
        
        $arsipList = $query->orderBy('submitted_at', 'desc')->paginate(12);
        
        return view('arsip.search', compact(
            'arsipList', 'kapalList', 'kapalId', 'status', 
            'bulan', 'tahun', 'searchTerm'
        ));
    }
    
    /**
     * Show specific arsip detail
     */
    public function show($id)
    {
        $arsip = Sertijab::with([
            'mutasi.kapal',
            'mutasi.abkNaik',
            'mutasi.abkTurun',
            'mutasi.jabatanTetapNaik',
            'mutasi.jabatanTetapTurun',
            'mutasi.jabatanMutasi',
            'adminVerifikator'
        ])->findOrFail($id);
        
        return view('arsip.show', compact('arsip'));
    }
    
    /**
     * Verify specific document type
     */
    public function verifyDocument(Request $request, $id)
    {
        $request->validate([
            'document_type' => 'required|in:sertijab,familisasi,lampiran',
            'status' => 'required|in:draft,final',
            'catatan' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $sertijab = Sertijab::findOrFail($id);
            $adminNrp = auth()->user()->NRP_admin ?? 1;
            
            // Verify specific document
            $success = $sertijab->verifyDocument($request->document_type, $request->status);
            
            if ($success) {
                // Update admin comment if provided
                if ($request->catatan) {
                    $sertijab->updateAdminComment($request->catatan, $adminNrp);
                }
                
                $sertijab->updated_by_admin = $adminNrp;
                $sertijab->save();
                
                DB::commit();
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Dokumen berhasil diverifikasi',
                        'status_dokumen' => $sertijab->fresh()->status_dokumen,
                        'verification_progress' => $sertijab->fresh()->verification_progress
                    ]);
                }
                
                return redirect()->back()->with('success', 'Dokumen berhasil diverifikasi');
            } else {
                throw new \Exception('Gagal memverifikasi dokumen');
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error verifying document: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memverifikasi dokumen: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal memverifikasi dokumen');
        }
    }
    
    /**
     * Verify all documents at once
     */
    public function verifyAllDocuments(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);
        
        try {
            DB::beginTransaction();
            
            $sertijab = Sertijab::findOrFail($id);
            $adminNrp = auth()->user()->NRP_admin ?? 1;
            
            $success = $sertijab->verifyAllDocuments($request->catatan, $adminNrp);
            
            if ($success) {
                DB::commit();
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Semua dokumen berhasil diverifikasi',
                        'status_dokumen' => $sertijab->fresh()->status_dokumen,
                        'verification_progress' => $sertijab->fresh()->verification_progress
                    ]);
                }
                
                return redirect()->back()->with('success', 'Semua dokumen berhasil diverifikasi');
            } else {
                throw new \Exception('Tidak ada dokumen yang bisa diverifikasi');
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error verifying all documents: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memverifikasi dokumen: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal memverifikasi dokumen');
        }
    }
    
    /**
     * Update arsip status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_dokumen' => 'required|in:draft,partial,final',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);
        
        try {
            DB::beginTransaction();
            
            $sertijab = Sertijab::findOrFail($id);
            $adminNrp = auth()->user()->NRP_admin ?? 1;
            
            $sertijab->status_dokumen = $request->status_dokumen;
            $sertijab->catatan_admin = $request->catatan_admin;
            $sertijab->updated_by_admin = $adminNrp;
            
            // If setting to final, also verify all documents
            if ($request->status_dokumen === 'final') {
                $sertijab->markAsVerified($adminNrp);
            }
            
            $sertijab->save();
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diupdate',
                    'status_dokumen' => $sertijab->status_dokumen,
                    'status_text' => $sertijab->status_text
                ]);
            }
            
            return redirect()->back()->with('success', 'Status berhasil diupdate');
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating status: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate status: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal mengupdate status');
        }
    }
    
    /**
     * Bulk update status for multiple arsip
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:draft,partial,final',
            'notes' => 'nullable|string|max:1000',
            'sertijab_ids' => 'required|array',
            'sertijab_ids.*' => 'exists:sertijab,id',
        ]);
        
        try {
            DB::beginTransaction();
            
            $adminNrp = auth()->user()->NRP_admin ?? 1;
            $updatedCount = 0;
            
            foreach ($request->sertijab_ids as $id) {
                $sertijab = Sertijab::find($id);
                if ($sertijab) {
                    $sertijab->status_dokumen = $request->status;
                    $sertijab->catatan_admin = $request->notes;
                    $sertijab->updated_by_admin = $adminNrp;
                    
                    if ($request->status === 'final') {
                        $sertijab->markAsVerified($adminNrp);
                    }
                    
                    $sertijab->save();
                    $updatedCount++;
                }
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', "Berhasil mengupdate status {$updatedCount} dokumen");
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error bulk updating status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupdate status secara massal');
        }
    }
    
    /**
     * Generate laporan arsip
     */
    public function generateLaporan(Request $request)
    {
        $kapalId = $request->input('kapal_id');
        $status = $request->input('status', 'all');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
        
        // Build query for laporan
        $query = Sertijab::with([
            'mutasi.kapal',
            'mutasi.abkNaik',
            'mutasi.abkTurun',
            'mutasi.jabatanTetapNaik',
            'mutasi.jabatanTetapTurun',
            'mutasi.jabatanMutasi',
            'adminVerifikator'
        ])->whereNotNull('submitted_at');
        
        // Apply filters
        if ($kapalId) {
            $query->whereHas('mutasi', function($q) use ($kapalId) {
                $q->where('id_kapal', $kapalId);
            });
        }
        
        if ($status !== 'all') {
            $query->where('status_dokumen', $status);
        }
        
        if ($bulan) {
            $query->whereMonth('submitted_at', $bulan);
        }
        
        if ($tahun) {
            $query->whereYear('submitted_at', $tahun);
        }
        
        $arsipList = $query->orderBy('submitted_at', 'desc')->get();
        
        return view('arsip.laporan', compact(
            'arsipList', 'kapalId', 'status', 'bulan', 'tahun'
        ));
    }

    /**
     * Create new arsip
     */
    public function create()
    {
        // Get kapal list untuk dropdown
        $kapalList = Kapal::select('id', 'nama_kapal')
            ->orderBy('nama_kapal')
            ->get();
        
        return view('arsip.create', compact('kapalList'));
    }

    /**
     * Get mutasi list by kapal
     */
    public function getMutasiByKapal(Request $request)
{
    $kapalId = $request->input('kapal_id');
    
    if (!$kapalId) {
        return response()->json([
            'success' => false,
            'message' => 'Kapal ID tidak ditemukan'
        ]);
    }
    
    try {
        Log::info("Fetching mutasi for kapal ID: {$kapalId}");
        
        // Cek dulu apakah ada data mutasi untuk kapal ini
        $mutasiCount = DB::table('mutasi_new')->where('id_kapal', $kapalId)->count();
        Log::info("Total mutasi found: {$mutasiCount}");
        
        if ($mutasiCount === 0) {
            return response()->json([
                'success' => true,
                'data' => [],
                'total' => 0,
                'message' => 'Tidak ada data mutasi untuk kapal ini'
            ]);
        }
        
        // Query dengan join manual untuk menghindari masalah relasi
        $mutasiList = DB::table('mutasi_new as m')
            ->leftJoin('kapal as k', 'm.id_kapal', '=', 'k.id')
            ->leftJoin('jabatan as j1', 'm.jabatan_tetap_naik', '=', 'j1.id')
            ->leftJoin('jabatan as j2', 'm.jabatan_tetap_turun', '=', 'j2.id')
            ->leftJoin('jabatan as j3', 'm.id_jabatan_mutasi', '=', 'j3.id')
            ->leftJoin('jabatan as j4', 'm.id_jabatan_mutasi_turun', '=', 'j4.id')
            ->leftJoin('sertijab_new as s', 'm.id', '=', 's.id_mutasi')
            ->select(
                'm.id',
                'm.nama_mutasi',
                'm.jenis_mutasi',
                'm.TMT',
                'm.TAT',
                'm.id_abk_naik',
                'm.nama_lengkap_naik',
                'm.id_abk_turun',
                'm.nama_lengkap_turun',
                'm.ada_abk_turun',
                'm.status_mutasi',
                'm.TMT_turun',
                'm.TAT_turun',
                'k.nama_kapal',
                'j1.nama_jabatan as jabatan_naik_nama',
                'j2.nama_jabatan as jabatan_turun_nama', 
                'j3.nama_jabatan as jabatan_mutasi_nama',
                'j4.nama_jabatan as jabatan_mutasi_turun_nama',
                's.id as sertijab_id',
                's.status_dokumen as sertijab_status'
            )
            ->where('m.id_kapal', $kapalId)
            ->where(function($query) {
                $query->where('m.status_mutasi', '!=', 'Ditolak')
                      ->orWhereNull('m.status_mutasi');
            })
            ->where(function($query) {
                $query->whereNull('s.status_dokumen')
                      ->orWhere('s.status_dokumen', '!=', 'final');
            })
            ->orderBy('m.created_at', 'desc')
            ->get();
        
        Log::info("Query result count: " . $mutasiList->count());
        
        // Format data untuk frontend
        $formattedData = $mutasiList->map(function($mutasi) {
            // Format periode mutasi
            $periode = '';
            if ($mutasi->TMT && $mutasi->TAT) {
                $periode = date('d/m/Y', strtotime($mutasi->TMT)) . ' - ' . date('d/m/Y', strtotime($mutasi->TAT));
            }
            
            return [
                'id' => $mutasi->id,
                'nama_mutasi' => $mutasi->nama_mutasi ?? 'N/A',
                'jenis_mutasi' => $mutasi->jenis_mutasi ?? 'N/A',
                'periode_mutasi' => $periode,
                'abk_naik' => [
                    'nrp' => $mutasi->id_abk_naik ?? 'N/A',
                    'nama' => $mutasi->nama_lengkap_naik ?? 'N/A',
                    'jabatan' => $mutasi->jabatan_naik_nama ?? 'N/A',
                    'jabatan_mutasi' => $mutasi->jabatan_mutasi_nama ?? 'N/A'
                ],
                'abk_turun' => $mutasi->ada_abk_turun ? [
                    'nrp' => $mutasi->id_abk_turun ?? 'N/A',
                    'nama' => $mutasi->nama_lengkap_turun ?? 'N/A',
                    'jabatan' => $mutasi->jabatan_turun_nama ?? 'N/A'
                ] : null,
                'ada_abk_turun' => (bool)$mutasi->ada_abk_turun,
                'status_mutasi' => $mutasi->status_mutasi ?? 'Draft',
                'has_sertijab' => $mutasi->sertijab_id ? true : false,
                'sertijab_status' => $mutasi->sertijab_status ?? null
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $formattedData->values(),
            'total' => $formattedData->count()
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error getting mutasi by kapal: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        Log::error('Kapal ID: ' . $kapalId);
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat mengambil data mutasi: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Store new arsip
     */
    public function store(Request $request)
{
    // Debug request data
    Log::info('Arsip store request data:', $request->all());
    Log::info('Files in request:', $request->allFiles());
    
    $request->validate([
        'mutasi_id' => 'required|exists:mutasi_new,id', // Perbaikan: gunakan tabel yang benar
        'dokumen_sertijab' => 'required|file|mimes:pdf|max:10240',
        'dokumen_familisasi' => 'nullable|file|mimes:pdf|max:10240',
        'dokumen_lampiran' => 'nullable|file|mimes:pdf|max:10240',
        'keterangan_dokumen' => 'nullable|string|max:1000',
        'status_verifikasi' => 'required|in:draft,final',
        'catatan_admin' => 'nullable|string|max:1000',
    ]);
    
    try {
        DB::beginTransaction();
        
        // 1. Get mutasi data dari tabel yang benar
        $mutasi = DB::table('mutasi_new')->where('id', $request->mutasi_id)->first();
        
        if (!$mutasi) {
            throw new \Exception('Data mutasi tidak ditemukan');
        }
        
        // 2. Check if sertijab already exists
        $existingSertijab = DB::table('sertijab_new')->where('id_mutasi', $request->mutasi_id)->first();
        
        if ($existingSertijab) {
            throw new \Exception('Dokumen sertijab untuk mutasi ini sudah ada');
        }
        
        // 3. Prepare sertijab data
        $sertijabData = [
            'id_mutasi' => $request->mutasi_id,
            'submitted_at' => now(),
            'updated_by_admin' => auth()->user()->NRP_admin ?? 1,
            'catatan_admin' => $request->catatan_admin,
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        // 4. Upload documents
        if ($request->hasFile('dokumen_sertijab')) {
            $sertijabPath = $request->file('dokumen_sertijab')->store('dokumen/sertijab', 'public');
            $sertijabData['dokumen_sertijab_path'] = $sertijabPath;
            $sertijabData['status_sertijab'] = $request->status_verifikasi;
        }
        
        if ($request->hasFile('dokumen_familisasi')) {
            $familisasiPath = $request->file('dokumen_familisasi')->store('dokumen/familisasi', 'public');
            $sertijabData['dokumen_familisasi_path'] = $familisasiPath;
            $sertijabData['status_familisasi'] = $request->status_verifikasi;
        }
        
        if ($request->hasFile('dokumen_lampiran')) {
            $lampiranPath = $request->file('dokumen_lampiran')->store('dokumen/lampiran', 'public');
            $sertijabData['dokumen_lampiran_path'] = $lampiranPath;
            $sertijabData['status_lampiran'] = $request->status_verifikasi;
        }
        
        // 5. Set verification status
        if ($request->status_verifikasi === 'final') {
            $sertijabData['verified_at'] = now();
            $sertijabData['verified_by_admin_nrp'] = auth()->user()->NRP_admin ?? 1;
            $sertijabData['status_dokumen'] = 'final';
        } else {
            $sertijabData['status_dokumen'] = 'draft';
        }
        
        // 6. Insert to database
        $sertijabId = DB::table('sertijab_new')->insertGetId($sertijabData);
        
        DB::commit();
        
        Log::info('Arsip created successfully with ID: ' . $sertijabId);
        
        return response()->json([
            'success' => true,
            'message' => 'Dokumen arsip berhasil disimpan',
            'redirect_url' => route('arsip.search')
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollback();
        Log::error('Validation errors:', $e->errors());
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan validasi',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error storing arsip: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan dokumen: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Edit arsip
     */
    public function edit($id)
    {
        $arsip = Sertijab::with([
            'mutasi.kapal',
            'mutasi.abkNaik',
            'mutasi.abkTurun',
            'mutasi.jabatanTetapNaik',
            'mutasi.jabatanTetapTurun',
            'mutasi.jabatanMutasi'
        ])->findOrFail($id);
        
        $kapalList = Kapal::orderBy('nama_kapal')->get();
        
        return view('arsip.edit', compact('arsip', 'kapalList'));
    }

    /**
     * Update arsip
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'dokumen_sertijab' => 'nullable|file|mimes:pdf|max:10240',
            'dokumen_familisasi' => 'nullable|file|mimes:pdf|max:10240',
            'dokumen_lampiran' => 'nullable|file|mimes:pdf|max:10240',
            'catatan_admin' => 'nullable|string|max:1000',
            'status_dokumen' => 'nullable|in:draft,partial,final',
        ]);
        
        try {
            DB::beginTransaction();
            
            $sertijab = Sertijab::findOrFail($id);
            $sertijab->catatan_admin = $request->catatan_admin;
            $sertijab->updated_by_admin = auth()->user()->NRP_admin ?? 1;
            
            if ($request->status_dokumen) {
                $sertijab->status_dokumen = $request->status_dokumen;
            }
            
            // Handle file uploads
            if ($request->hasFile('dokumen_sertijab')) {
                // Delete old file
                if ($sertijab->dokumen_sertijab_path) {
                    Storage::disk('public')->delete($sertijab->dokumen_sertijab_path);
                }
                $sertijab->dokumen_sertijab_path = $request->file('dokumen_sertijab')->store('sertijab', 'public');
                $sertijab->status_sertijab = 'draft';
            }
            
            if ($request->hasFile('dokumen_familisasi')) {
                // Delete old file
                if ($sertijab->dokumen_familisasi_path) {
                    Storage::disk('public')->delete($sertijab->dokumen_familisasi_path);
                }
                $sertijab->dokumen_familisasi_path = $request->file('dokumen_familisasi')->store('familisasi', 'public');
                $sertijab->status_familisasi = 'draft';
            }
            
            if ($request->hasFile('dokumen_lampiran')) {
                // Delete old file
                if ($sertijab->dokumen_lampiran_path) {
                    Storage::disk('public')->delete($sertijab->dokumen_lampiran_path);
                }
                $sertijab->dokumen_lampiran_path = $request->file('dokumen_lampiran')->store('lampiran', 'public');
                $sertijab->status_lampiran = 'draft';
            }
            
            $sertijab->save();
            
            DB::commit();
            
            return redirect()->route('arsip.show', $id)->with('success', 'Arsip berhasil diupdate');
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating arsip: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupdate arsip')->withInput();
        }
    }

    /**
     * Delete arsip
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $sertijab = Sertijab::findOrFail($id);
            
            // Delete files
            if ($sertijab->dokumen_sertijab_path) {
                Storage::disk('public')->delete($sertijab->dokumen_sertijab_path);
            }
            if ($sertijab->dokumen_familisasi_path) {
                Storage::disk('public')->delete($sertijab->dokumen_familisasi_path);
            }
            if ($sertijab->dokumen_lampiran_path) {
                Storage::disk('public')->delete($sertijab->dokumen_lampiran_path);
            }
            
            $sertijab->delete();
            
            DB::commit();
            
            return redirect()->route('arsip.search')->with('success', 'Arsip berhasil dihapus');
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting arsip: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus arsip');
        }
    }
    
    /**
     * Get arsip statistics
     */
    private function getArsipStats()
    {
        $totalArsip = Sertijab::whereNotNull('submitted_at')->count();
        $finalArsip = Sertijab::where('status_dokumen', 'final')->count();
        $draftArsip = Sertijab::where('status_dokumen', 'draft')->count();
        $pendingVerification = Sertijab::where('status_dokumen', 'partial')->count();
        
        $completionRate = $totalArsip > 0 ? round(($finalArsip / $totalArsip) * 100) : 0;
        
        return [
            'total_arsip' => $totalArsip,
            'final_arsip' => $finalArsip,
            'draft_arsip' => $draftArsip,
            'pending_verification' => $pendingVerification,
            'rejected_documents' => 0, // Add logic if needed
            'completion_rate' => $completionRate
        ];
    }
    
    /**
     * Get monthly arsip data for chart
     */
    private function getMonthlyArsipData()
    {
        $currentYear = date('Y');
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $count = Sertijab::whereYear('submitted_at', $currentYear)
                           ->whereMonth('submitted_at', $month)
                           ->count();
            $monthlyData[] = $count;
        }
        
        return $monthlyData;
    }
}