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
            // Get mutasi yang belum ada dokumen sertijabnya atau status draft
            $mutasiList = Mutasi::with([
                'kapal',
                'jabatanTetapNaik',
                'jabatanTetapTurun',
                'jabatanMutasi',
                'sertijab'
            ])
            ->where('id_kapal', $kapalId)
            ->where('status_mutasi', '!=', 'Ditolak')
            ->whereDoesntHave('sertijab', function($query) {
                $query->where('status_dokumen', 'final');
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($mutasi) {
                return [
                    'id' => $mutasi->id,
                    'nama_mutasi' => $mutasi->nama_mutasi,
                    'jenis_mutasi' => $mutasi->jenis_mutasi,
                    'periode_mutasi' => $mutasi->periode_mutasi_naik,
                    'abk_naik' => [
                        'nrp' => $mutasi->id_abk_naik,
                        'nama' => $mutasi->nama_lengkap_naik,
                        'jabatan' => $mutasi->jabatanTetapNaik->nama_jabatan ?? 'N/A',
                        'jabatan_mutasi' => $mutasi->jabatanMutasi->nama_jabatan ?? 'N/A'
                    ],
                    'abk_turun' => $mutasi->ada_abk_turun ? [
                        'nrp' => $mutasi->id_abk_turun,
                        'nama' => $mutasi->nama_lengkap_turun,
                        'jabatan' => $mutasi->jabatanTetapTurun->nama_jabatan ?? 'N/A'
                    ] : null,
                    'ada_abk_turun' => $mutasi->ada_abk_turun,
                    'status_mutasi' => $mutasi->status_mutasi,
                    'has_sertijab' => $mutasi->sertijab ? true : false,
                    'sertijab_status' => $mutasi->sertijab ? $mutasi->sertijab->status_dokumen : null
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $mutasiList,
                'total' => $mutasiList->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting mutasi by kapal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data mutasi'
            ]);
        }
    }

    /**
     * Store new arsip
     */
    public function store(Request $request)
    {
        $request->validate([
            'mutasi_id' => 'required|exists:mutasi,id',
            'dokumen_sertijab' => 'required|file|mimes:pdf|max:10240',
            'dokumen_familisasi' => 'nullable|file|mimes:pdf|max:10240',
            'dokumen_lampiran' => 'nullable|file|mimes:pdf|max:10240',
            'keterangan_dokumen' => 'nullable|string|max:1000',
            'status_verifikasi' => 'required|in:draft,final',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);
        
        try {
            DB::beginTransaction();
            
            // 1. Get mutasi data
            $mutasi = Mutasi::findOrFail($request->mutasi_id);
            
            // 2. Check if sertijab already exists
            $sertijab = $mutasi->sertijab;
            
            if (!$sertijab) {
                // Create new sertijab record
                $sertijab = new Sertijab();
                $sertijab->id_mutasi = $mutasi->id;
            }
            
            // 3. Upload documents
            if ($request->hasFile('dokumen_sertijab')) {
                // Delete old file if exists
                if ($sertijab->dokumen_sertijab_path) {
                    Storage::disk('public')->delete($sertijab->dokumen_sertijab_path);
                }
                $sertijab->dokumen_sertijab_path = $request->file('dokumen_sertijab')->store('dokumen/sertijab', 'public');
                $sertijab->status_sertijab = $request->status_verifikasi;
            }
            
            if ($request->hasFile('dokumen_familisasi')) {
                if ($sertijab->dokumen_familisasi_path) {
                    Storage::disk('public')->delete($sertijab->dokumen_familisasi_path);
                }
                $sertijab->dokumen_familisasi_path = $request->file('dokumen_familisasi')->store('dokumen/familisasi', 'public');
                $sertijab->status_familisasi = $request->status_verifikasi;
            }
            
            if ($request->hasFile('dokumen_lampiran')) {
                if ($sertijab->dokumen_lampiran_path) {
                    Storage::disk('public')->delete($sertijab->dokumen_lampiran_path);
                }
                $sertijab->dokumen_lampiran_path = $request->file('dokumen_lampiran')->store('dokumen/lampiran', 'public');
                $sertijab->status_lampiran = $request->status_verifikasi;
            }
            
            // 4. Set document metadata
            $sertijab->catatan_admin = $request->catatan_admin;
            $sertijab->submitted_at = now();
            $sertijab->updated_by_admin = auth()->user()->NRP_admin ?? 1;
            
            if ($request->status_verifikasi === 'final') {
                $sertijab->verified_at = now();
                $sertijab->verified_by_admin_nrp = auth()->user()->NRP_admin ?? 1;
            }
            
            $sertijab->save();
            
            // 5. Update overall status
            $sertijab->updateOverallStatus();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Dokumen arsip berhasil disimpan',
                'redirect_url' => route('arsip.search')
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error storing arsip: ' . $e->getMessage());
            
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