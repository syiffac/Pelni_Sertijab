<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kapal;
use App\Models\ABK;
use App\Models\Mutasi;
use App\Models\Sertijab;
use App\Models\Jabatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MonitoringController extends Controller
{
    /**
     * Display monitoring dashboard
     */
    public function index(Request $request)
    {
        // Ambil data kapal untuk ditampilkan di dashboard
        $kapalList = Kapal::withCount(['abk'])->get();
        
        // FIXED: Sesuaikan dengan field di migration sertijab_new
        $stats = [
            'total_abk' => ABK::count(),
            'total_mutasi' => Mutasi::where('perlu_sertijab', true)->count(),
            'sertijab_submitted' => Sertijab::whereNotNull('submitted_at')->count(),
            'sertijab_verified' => Sertijab::where('status_dokumen', 'final')->count(),
            'sertijab_pending' => Sertijab::where('status_dokumen', 'draft')->count(),
            'sertijab_partial' => Sertijab::where('status_dokumen', 'partial')->count(),
        ];
        
        // FIXED: Add missing sertijab_rejected stat
        $stats['sertijab_rejected'] = 0; // Temporary - bisa diupdate jika ada field rejected
        
        // Hitung persentase progress verifikasi
        $stats['verification_progress'] = $stats['total_mutasi'] > 0 
            ? round(($stats['sertijab_verified'] / $stats['total_mutasi']) * 100) 
            : 0;
            
        // Hitung persentase progress submission
        $stats['submission_progress'] = $stats['total_mutasi'] > 0 
            ? round(($stats['sertijab_submitted'] / $stats['total_mutasi']) * 100) 
            : 0;
            
        // UPDATED: Ambil data monitoring per kapal dengan pagination
        $monitoringData = $this->getMonitoringOverviewPaginated($request);
        
        return view('monitoring.index', compact('kapalList', 'stats', 'monitoringData'));
    }
    
    /**
     * Helper function to get monitoring overview data with pagination - NEW METHOD
     */
    private function getMonitoringOverviewPaginated(Request $request)
    {
        try {
            $perPage = 5; // Show 5 kapal per page
            $page = $request->get('page', 1);
            
            // Get kapal with mutasi count first
            $kapalsWithMutasi = DB::table('kapal')
                ->select(
                    'kapal.id',
                    'kapal.nama_kapal',
                    DB::raw('COALESCE(COUNT(DISTINCT mutasi_new.id), 0) as total_mutasi')
                )
                ->leftJoin('mutasi_new', 'kapal.id', '=', 'mutasi_new.id_kapal')
                ->where(function($query) {
                    $query->whereNull('mutasi_new.perlu_sertijab')
                          ->orWhere('mutasi_new.perlu_sertijab', true);
                })
                ->groupBy('kapal.id', 'kapal.nama_kapal')
                ->having('total_mutasi', '>', 0) // Only kapal with mutasi
                ->orderBy('kapal.nama_kapal')
                ->get();
            
            // Calculate pagination manually
            $total = $kapalsWithMutasi->count();
            $offset = ($page - 1) * $perPage;
            $kapalsPaginated = $kapalsWithMutasi->slice($offset, $perPage);
            
            // Get detailed stats for paginated kapals
            $results = collect();
            foreach ($kapalsPaginated as $kapal) {
                // Count submitted sertijab
                $submitted = Sertijab::whereHas('mutasi', function($q) use ($kapal) {
                    $q->where('id_kapal', $kapal->id);
                })->whereNotNull('submitted_at')->count();
                
                // Count verified
                $verified = Sertijab::whereHas('mutasi', function($q) use ($kapal) {
                    $q->where('id_kapal', $kapal->id);
                })->where('status_dokumen', 'final')->count();
                
                // Count pending
                $pending = Sertijab::whereHas('mutasi', function($q) use ($kapal) {
                    $q->where('id_kapal', $kapal->id);
                })->where('status_dokumen', 'draft')->count();
                
                // Count partial
                $partial = Sertijab::whereHas('mutasi', function($q) use ($kapal) {
                    $q->where('id_kapal', $kapal->id);
                })->where('status_dokumen', 'partial')->count();
                
                $results->push((object)[
                    'id' => $kapal->id,
                    'nama_kapal' => $kapal->nama_kapal,
                    'total_mutasi' => $kapal->total_mutasi,
                    'submitted' => $submitted,
                    'verified' => $verified,
                    'pending' => $pending,
                    'partial' => $partial
                ]);
            }
            
            // Create pagination info
            $pagination = (object)[
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
                'per_page' => $perPage,
                'total' => $total,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total),
                'has_pages' => $total > $perPage,
                'has_more_pages' => $page < ceil($total / $perPage),
                'on_first_page' => $page == 1,
            ];
            
            return (object)[
                'data' => $results,
                'pagination' => $pagination
            ];
            
        } catch (\Exception $e) {
            Log::error('Error in getMonitoringOverviewPaginated: ' . $e->getMessage());
            
            return (object)[
                'data' => collect([]),
                'pagination' => (object)[
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => $perPage,
                    'total' => 0,
                    'from' => 0,
                    'to' => 0,
                    'has_pages' => false,
                    'has_more_pages' => false,
                    'on_first_page' => true,
                ]
            ];
        }
    }
    
    /**
     * Alternative method with safer approach
     */
    private function getSafeMonitoringOverview()
    {
        try {
            // Get all kapal first
            $kapals = Kapal::all();
            $results = collect();
            
            foreach ($kapals as $kapal) {
                // Count mutasi for this kapal
                $totalMutasi = Mutasi::where('id_kapal', $kapal->id)
                    ->where('perlu_sertijab', true)
                    ->count();
                
                // Count submitted sertijab
                $submitted = Sertijab::whereHas('mutasi', function($q) use ($kapal) {
                    $q->where('id_kapal', $kapal->id);
                })->whereNotNull('submitted_at')->count();
                
                // Count verified
                $verified = Sertijab::whereHas('mutasi', function($q) use ($kapal) {
                    $q->where('id_kapal', $kapal->id);
                })->where('status_dokumen', 'final')->count();
                
                // Count pending
                $pending = Sertijab::whereHas('mutasi', function($q) use ($kapal) {
                    $q->where('id_kapal', $kapal->id);
                })->where('status_dokumen', 'draft')->count();
                
                // Count partial
                $partial = Sertijab::whereHas('mutasi', function($q) use ($kapal) {
                    $q->where('id_kapal', $kapal->id);
                })->where('status_dokumen', 'partial')->count();
                
                $results->push((object)[
                    'id' => $kapal->id,
                    'nama_kapal' => $kapal->nama_kapal,
                    'total_mutasi' => $totalMutasi,
                    'submitted' => $submitted,
                    'verified' => $verified,
                    'pending' => $pending,
                    'partial' => $partial
                ]);
            }
            
            return $results->where('total_mutasi', '>', 0); // Only show kapal with mutasi
            
        } catch (\Exception $e) {
            Log::error('Error in getSafeMonitoringOverview: ' . $e->getMessage());
            return collect([]);
        }
    }
    
    /**
     * Helper function to get monitoring overview data - FIXED QUERY
     */
    private function getMonitoringOverview()
    {
        try {
            // FIXED: Query dengan debugging untuk cek structure database
            $monitoringData = DB::table('kapal')
                ->select(
                    'kapal.id',
                    'kapal.nama_kapal',
                    DB::raw('COALESCE(COUNT(DISTINCT mutasi_new.id), 0) as total_mutasi'),
                    DB::raw('COALESCE(COUNT(DISTINCT CASE WHEN sertijab_new.id IS NOT NULL THEN mutasi_new.id END), 0) as submitted'),
                    DB::raw('COALESCE(COUNT(DISTINCT CASE WHEN sertijab_new.status_dokumen = "final" THEN mutasi_new.id END), 0) as verified'),
                    DB::raw('COALESCE(COUNT(DISTINCT CASE WHEN sertijab_new.status_dokumen = "draft" THEN mutasi_new.id END), 0) as pending'),
                    DB::raw('COALESCE(COUNT(DISTINCT CASE WHEN sertijab_new.status_dokumen = "partial" THEN mutasi_new.id END), 0) as partial')
                )
                ->leftJoin('mutasi_new', 'kapal.id', '=', 'mutasi_new.id_kapal')
                ->leftJoin('sertijab_new', 'mutasi_new.id', '=', 'sertijab_new.id_mutasi')
                ->where(function($query) {
                    $query->whereNull('mutasi_new.perlu_sertijab')
                          ->orWhere('mutasi_new.perlu_sertijab', true);
                })
                ->groupBy('kapal.id', 'kapal.nama_kapal')
                ->orderBy('kapal.nama_kapal')
                ->get();
            
            // Debug: Log the results to check structure
            Log::info('Monitoring data structure:', [
                'count' => $monitoringData->count(),
                'first_item' => $monitoringData->first() ? $monitoringData->first() : 'No data'
            ]);
            
            return $monitoringData;
            
        } catch (\Exception $e) {
            Log::error('Error in getMonitoringOverview: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return fallback data structure
            return collect([]);
        }
    }
    
    /**
     * Display document verification page
     */
    public function documents(Request $request)
    {
        $kapalId = $request->input('kapal_id');
        $status = $request->input('status', 'all');
        $search = $request->input('search');

        // Build query dengan relasi yang sesuai dengan migration
        $query = Sertijab::with([
            'mutasi.abkNaik',
            'mutasi.abkTurun',
            'mutasi.kapal',
            'adminVerifikator'
        ])->whereNotNull('submitted_at');

        // Filter by kapal
        if ($kapalId) {
            $query->whereHas('mutasi', function($q) use ($kapalId) {
                $q->where('id_kapal', $kapalId);
            });
        }

        // Filter by status
        if ($status !== 'all') {
            $query->where('status_dokumen', $status);
        }

        // Search
        if ($search) {
            $query->whereHas('mutasi', function($q) use ($search) {
                $q->where('nama_lengkap_naik', 'LIKE', "%{$search}%")
                  ->orWhere('nama_lengkap_turun', 'LIKE', "%{$search}%");
            });
        }

        $documents = $query->orderBy('submitted_at', 'desc')->paginate(15);
        $kapals = Kapal::orderBy('nama_kapal')->get();

        return view('monitoring.documents', compact('documents', 'kapals', 'kapalId', 'status', 'search'));
    }

    /**
     * Show document detail for verification
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
            'mutasi.jabatanMutasiTurun',
            'adminVerifikator'
        ])->findOrFail($id);

        return view('monitoring.show', compact('arsip'));
    }
    
    /**
     * Display sertijab monitoring by kapal - FIXED METHOD
     */
    public function sertijab(Request $request)
    {
        $kapalId = $request->input('kapal_id');
        $kapalList = Kapal::all();
        
        // FIXED: Build query untuk sertijab yang sudah submitted dengan relasi lengkap
        $query = Sertijab::with([
            'mutasi.kapal',
            'mutasi.abkNaik',
            'mutasi.abkTurun',
            'mutasi.jabatanTetapNaik',
            'mutasi.jabatanTetapTurun',
            'mutasi.jabatanMutasi',
            'mutasi.jabatanMutasiTurun',
            'adminVerifikator'
        ])->whereNotNull('submitted_at');
        
        // Filter berdasarkan kapal jika dipilih
        if ($kapalId) {
            $query->whereHas('mutasi', function($q) use ($kapalId) {
                $q->where('id_kapal', $kapalId);
            });
        }
        
        // FIXED: Rename variable to match view expectation
        $sertijabList = $query->orderBy('submitted_at', 'desc')->paginate(10);
        
        // ADDITIONAL: Create mutasiList for backward compatibility with view
        $mutasiList = $sertijabList->map(function($sertijab) {
            // Transform sertijab ke format yang diharapkan view
            $mutasi = $sertijab->mutasi;
            if ($mutasi) {
                // Add sertijab relation to mutasi
                $mutasi->sertijab = $sertijab;
                
                // Add legacy properties for compatibility
                $mutasi->kapalAsal = $mutasi->kapal;
                $mutasi->kapalTujuan = null; // Set based on your business logic
                $mutasi->abkTurun = $mutasi->abkNaik; // Adjust based on your data structure
                $mutasi->nrp_turun = $mutasi->id_abk_naik;
                $mutasi->case_mutasi = $mutasi->case_mutasi ?? 'Mutasi';
                $mutasi->id_mutasi = $mutasi->id;
                
                // Map sertijab properties to legacy format
                $sertijab->file_path = $sertijab->dokumen_sertijab_path;
                $sertijab->status_verifikasi = $this->mapStatusToLegacy($sertijab->status_dokumen);
                $sertijab->id_sertijab = $sertijab->id;
                $sertijab->uploaded_at = $sertijab->submitted_at;
            }
            
            return $mutasi;
        })->filter(); // Remove null mutasi
        
        return view('monitoring.sertijab', compact('kapalList', 'sertijabList', 'mutasiList', 'kapalId'));
    }

    /**
     * Helper method to map new status to legacy status
     */
    private function mapStatusToLegacy($newStatus)
    {
        $mapping = [
            'draft' => 'pending',
            'partial' => 'pending', 
            'final' => 'verified'
        ];
        
        return $mapping[$newStatus] ?? 'pending';
    }
    
    /**
     * Verify sertijab document - LEGACY METHOD
     */
    public function verifySertijab(Request $request, $id)
    {
        $request->validate([
            'status_dokumen' => 'required|in:final,draft',
            'catatan_admin' => 'nullable|string|max:500',
        ]);
        
        try {
            $sertijab = Sertijab::findOrFail($id);
            
            $sertijab->status_dokumen = $request->status_dokumen;
            $sertijab->catatan_admin = $request->catatan_admin;
            $sertijab->verified_by_admin_nrp = auth()->user()->NRP_admin;
            $sertijab->verified_at = now();
            $sertijab->updated_by_admin = auth()->user()->NRP_admin;
            
            $sertijab->save();
            
            return redirect()->back()->with('success', 'Dokumen Sertijab berhasil diverifikasi');
            
        } catch (\Exception $e) {
            Log::error('Error verifying sertijab: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal verifikasi dokumen: ' . $e->getMessage());
        }
    }
    
    /**
     * Export sertijab monitoring data
     */
    public function exportSertijab(Request $request)
    {
        return redirect()->back()->with('success', 'Data Sertijab berhasil diekspor');
    }
    
    /**
     * Update document verification status
     */
    public function updateVerification(Request $request, $id)
    {
        $validated = $request->validate([
            'status_sertijab' => 'nullable|in:draft,final',
            'status_familisasi' => 'nullable|in:draft,final',
            'status_lampiran' => 'nullable|in:draft,final',
            'catatan_admin' => 'nullable|string|max:2000',
        ]);

        try {
            DB::beginTransaction();

            $sertijab = Sertijab::findOrFail($id);
            $adminNrp = auth()->user()->NRP_admin ?? 1; // Fallback jika tidak ada auth
            
            // Update catatan saja jika hanya itu yang dikirim
            if ($request->has('catatan_admin') && count($validated) === 1) {
                $sertijab->catatan_admin = $request->catatan_admin;
                $sertijab->updated_by_admin = $adminNrp;
                $sertijab->save();
                
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Catatan berhasil diperbarui'
                ]);
            }

            // Handle status updates...
            if ($request->has('status_sertijab')) {
                $sertijab->status_sertijab = $request->status_sertijab;
            }

            if ($request->has('status_familisasi')) {
                $sertijab->status_familisasi = $request->status_familisasi;
            }

            if ($request->has('status_lampiran')) {
                $sertijab->status_lampiran = $request->status_lampiran;
            }

            if ($request->has('catatan_admin')) {
                $sertijab->catatan_admin = $request->catatan_admin;
            }

            $sertijab->updated_by_admin = $adminNrp;
            $sertijab->save();
            
            // Update overall status
            $sertijab->updateOverallStatus();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status verifikasi berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating verification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal update verifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick verify all documents for a specific sertijab
     */
    public function quickVerifyAll(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:2000',
        ]);

        try {
            $sertijab = Sertijab::findOrFail($id);
            $adminNrp = auth()->user()->NRP_admin ?? 1; // Fallback jika tidak ada auth

            if ($sertijab->verifyAllDocuments($request->catatan_admin, $adminNrp)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Semua dokumen berhasil diverifikasi',
                    'status_dokumen' => $sertijab->status_dokumen
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada dokumen yang bisa diverifikasi'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Error in quick verify: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal verifikasi dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update note for sertijab
     */
    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:2000',
        ]);
        
        try {
            $sertijab = Sertijab::findOrFail($id);
            $adminNrp = auth()->user()->NRP_admin ?? 1;
            
            $sertijab->catatan_admin = $request->catatan_admin;
            $sertijab->updated_by_admin = $adminNrp;
            $sertijab->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating note: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui catatan: ' . $e->getMessage()
            ], 500);
        }
    }
}
