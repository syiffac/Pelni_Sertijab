<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Kapal;
use App\Models\Sertijab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArsipController extends Controller
{
    /**
     * Display a listing of arsip sertijab
     */
    public function index()
    {
        try {
            // Statistik arsip
            $stats = $this->getArsipStatistics();
            
            // Data untuk chart bulanan
            $monthlyData = $this->getMonthlyArsipData();
            
            // Daftar kapal dengan statistik arsip - FIXED QUERY
            $kapalList = $this->getKapalWithArsipStats();
            
            return view('arsip.index', compact('stats', 'monthlyData', 'kapalList'));
            
        } catch (\Exception $e) {
            \Log::error('Error in ArsipController@index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return view dengan data default jika error
            return view('arsip.index', [
                'stats' => $this->getDefaultStats(),
                'monthlyData' => array_fill(0, 12, 0),
                'kapalList' => collect([])
            ])->with('error', 'Terjadi kesalahan saat memuat data arsip: ' . $e->getMessage());
        }
    }

    /**
     * Get arsip statistics
     */
    private function getArsipStatistics()
    {
        try {
            // Hitung statistik dari tabel sertijab_new dan mutasi_new
            $totalArsip = Sertijab::count();
            $finalArsip = Sertijab::where('status_dokumen', 'final')->count();
            $draftArsip = Sertijab::where('status_dokumen', 'draft')->count();
            $pendingVerification = Sertijab::where('status_dokumen', 'partial')->count();
            
            // Hitung completion rate
            $completionRate = $totalArsip > 0 ? round(($finalArsip / $totalArsip) * 100, 1) : 0;
            
            // Hitung dokumen yang ditolak (bisa dari catatan admin atau status khusus)
            $rejectedDocuments = Sertijab::where('catatan_admin', 'like', '%ditolak%')
                ->orWhere('catatan_admin', 'like', '%reject%')->count();
            
            return [
                'total_arsip' => $totalArsip,
                'final_arsip' => $finalArsip,
                'draft_arsip' => $draftArsip,
                'pending_verification' => $pendingVerification,
                'completion_rate' => $completionRate,
                'rejected_documents' => $rejectedDocuments
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error getting arsip statistics: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    /**
     * Get monthly arsip data for chart
     */
    private function getMonthlyArsipData()
    {
        try {
            $currentYear = date('Y');
            $monthlyData = [];
            
            // Ambil data per bulan dari tabel sertijab_new berdasarkan submitted_at
            for ($month = 1; $month <= 12; $month++) {
                $count = Sertijab::whereYear('submitted_at', $currentYear)
                    ->whereMonth('submitted_at', $month)
                    ->count();
                    
                $monthlyData[] = $count;
            }
            
            return $monthlyData;
            
        } catch (\Exception $e) {
            \Log::error('Error getting monthly arsip data: ' . $e->getMessage());
            return array_fill(0, 12, 0);
        }
    }

    /**
     * Get kapal list with arsip statistics - FIXED
     */
    private function getKapalWithArsipStats()
    {
        try {
            // FIXED: Gunakan kolom yang benar dari tabel mutasi_new
            $kapalList = Kapal::select('kapal.*')
                ->with(['mutasiNew' => function($query) {
                    $query->select('id_kapal', 'id') // Select minimal untuk performa
                          ->with(['sertijab' => function($sertijabQuery) {
                              $sertijabQuery->select('id_mutasi', 'status_dokumen');
                          }]);
                }])
                ->get()
                ->map(function($kapal) {
                    // Hitung statistik arsip untuk setiap kapal
                    $totalArsip = 0;
                    $finalArsip = 0;
                    $draftArsip = 0;
                    
                    // FIXED: Gunakan relasi mutasiNew dan hitung arsip dari sertijab
                    foreach ($kapal->mutasiNew as $mutasi) {
                        if ($mutasi->sertijab) {
                            $totalArsip++;
                            
                            switch ($mutasi->sertijab->status_dokumen) {
                                case 'final':
                                    $finalArsip++;
                                    break;
                                case 'draft':
                                case 'partial':
                                    $draftArsip++;
                                    break;
                            }
                        }
                    }
                    
                    // Tambahkan properti statistik ke objek kapal
                    $kapal->total_arsip = $totalArsip;
                    $kapal->final_arsip = $finalArsip;
                    $kapal->draft_arsip = $draftArsip;
                    
                    return $kapal;
                })
                ->sortByDesc('total_arsip'); // Urutkan berdasarkan total arsip
            
            return $kapalList;
            
        } catch (\Exception $e) {
            \Log::error('Error getting kapal with arsip stats: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return empty collection jika error
            return collect([]);
        }
    }

    /**
     * Search arsip by kapal
     */
    public function search(Request $request)
    {
        try {
            $kapalId = $request->get('kapal_id');
            $search = $request->get('search');
            $status = $request->get('status');
            $bulan = $request->get('bulan');
            $tahun = $request->get('tahun', date('Y'));
            $searchTerm = $search; // For blade compatibility
            
            // Base query - FIXED: Gunakan relasi yang benar
            $query = Sertijab::with([
                'mutasi' => function($mutasiQuery) {
                    $mutasiQuery->with(['kapal', 'abkNaik', 'abkTurun', 'jabatanMutasi']);
                }
            ]);
            
            // Filter by kapal - FIXED: Gunakan join yang benar
            if ($kapalId) {
                $query->whereHas('mutasi', function($mutasiQuery) use ($kapalId) {
                    $mutasiQuery->where('id_kapal', $kapalId); // FIXED: Gunakan id_kapal bukan id_kapal_asal
                });
            }
            
            // Filter by search term
            if ($search) {
                $query->whereHas('mutasi', function($mutasiQuery) use ($search) {
                    $mutasiQuery->where('nama_lengkap_naik', 'like', "%{$search}%")
                               ->orWhere('nama_lengkap_turun', 'like', "%{$search}%")
                               ->orWhere('id_abk_naik', 'like', "%{$search}%")
                               ->orWhere('id_abk_turun', 'like', "%{$search}%");
                });
            }
            
            // Filter by status
            if ($status && $status !== 'all') {
                $query->where('status_dokumen', $status);
            }
            
            // Filter by bulan
            if ($bulan) {
                $query->whereMonth('submitted_at', $bulan);
            }
            
            // Filter by tahun
            if ($tahun) {
                $query->whereYear('submitted_at', $tahun);
            }
            
            // Get results with pagination
            $arsipList = $query->orderBy('submitted_at', 'desc')
                              ->paginate(20);
            
            // Get kapal info jika ada filter kapal
            $selectedKapal = $kapalId ? Kapal::find($kapalId) : null;
            
            // FIXED: Get filter options - ini yang menyebabkan error
            $kapalList = Kapal::select('id', 'nama_kapal')->orderBy('nama_kapal')->get();
            
            $statusOptions = [
                'draft' => 'Draft',
                'partial' => 'Sebagian Terverifikasi', 
                'final' => 'Final'
            ];
            
            return view('arsip.search', compact(
                'arsipList', 
                'selectedKapal', 
                'kapalList',  // FIXED: Pastikan ini ada
                'statusOptions',
                'search',
                'searchTerm', // For blade compatibility
                'status',
                'kapalId',
                'bulan',
                'tahun'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error in arsip search: ' . $e->getMessage());
            
            // FIXED: Return dengan kapalList kosong jika error
            return view('arsip.search', [
                'arsipList' => collect([]),
                'selectedKapal' => null,
                'kapalList' => collect([]),
                'statusOptions' => [],
                'search' => '',
                'searchTerm' => '',
                'status' => '',
                'kapalId' => '',
                'bulan' => '',
                'tahun' => date('Y')
            ])->with('error', 'Terjadi kesalahan saat mencari arsip: ' . $e->getMessage());
        }
    }

    /**
     * Show specific arsip document
     */
    public function show($id)
    {
        try {
            $sertijab = Sertijab::with([
                'mutasi' => function($query) {
                    $query->with([
                        'kapal',
                        'abkNaik',
                        'abkTurun',
                        'jabatanTetapNaik',
                        'jabatanTetapTurun',
                        'jabatanMutasi',
                        'jabatanMutasiTurun'
                    ]);
                },
                'adminVerifikator',
                'adminUpdater'
            ])->findOrFail($id);
            
            return view('arsip.show', compact('sertijab'));
            
        } catch (\Exception $e) {
            \Log::error('Error showing arsip: ' . $e->getMessage());
            
            return redirect()->route('arsip.index')
                ->with('error', 'Arsip tidak ditemukan atau terjadi kesalahan.');
        }
    }

    /**
     * Generate laporan arsip - FIXED: Method yang benar
     */
    public function laporan(Request $request)
    {
        try {
            // Jika tidak ada parameter, tampilkan halaman under development
            if (!$request->hasAny(['periode_start', 'periode_end', 'kapal_id', 'status', 'format'])) {
                return view('arsip.laporan');
            }

            $request->validate([
                'periode_start' => 'nullable|date',
                'periode_end' => 'nullable|date|after_or_equal:periode_start',
                'kapal_id' => 'nullable|exists:kapal,id',
                'status' => 'nullable|in:draft,partial,final',
                'format' => 'nullable|in:pdf,excel'
            ]);
            
            // Build query
            $query = Sertijab::with([
                'mutasi' => function($mutasiQuery) {
                    $mutasiQuery->with(['kapal', 'abkNaik', 'abkTurun', 'jabatanMutasi']);
                }
            ]);
            
            // Apply filters
            if ($request->periode_start && $request->periode_end) {
                $query->whereBetween('submitted_at', [
                    $request->periode_start,
                    $request->periode_end . ' 23:59:59'
                ]);
            }
            
            if ($request->kapal_id) {
                $query->whereHas('mutasi', function($mutasiQuery) use ($request) {
                    $mutasiQuery->where('id_kapal', $request->kapal_id); // FIXED: Gunakan id_kapal
                });
            }
            
            if ($request->status) {
                $query->where('status_dokumen', $request->status);
            }
            
            $arsipList = $query->orderBy('submitted_at', 'desc')->get();
            
            // Generate laporan berdasarkan format
            if ($request->format === 'pdf') {
                return $this->generatePdfLaporan($arsipList, $request->all());
            } elseif ($request->format === 'excel') {
                return $this->generateExcelLaporan($arsipList, $request->all());
            }
            
            // Show form laporan dengan data
            $kapalOptions = Kapal::select('id', 'nama_kapal')->orderBy('nama_kapal')->get();
            
            return view('arsip.laporan-data', compact('kapalOptions', 'arsipList'));
            
        } catch (\Exception $e) {
            \Log::error('Error generating laporan: ' . $e->getMessage());
            
            return redirect()->route('arsip.index')
                ->with('error', 'Gagal generate laporan: ' . $e->getMessage());
        }
    }

    /**
     * Create manual arsip entry
     */
    public function create()
    {
        $kapalList = Kapal::select('id', 'nama_kapal')->orderBy('nama_kapal')->get();
        
        return view('arsip.create', compact('kapalList'));
    }

    /**
     * Store manual arsip entry
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mutasi' => 'required|exists:mutasi_new,id',
            'catatan_admin' => 'nullable|string|max:1000',
            'dokumen_sertijab' => 'nullable|file|mimes:pdf|max:5120',
            'dokumen_familisasi' => 'nullable|file|mimes:pdf|max:5120',
            'dokumen_lampiran' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        try {
            DB::beginTransaction();

            // Check if sertijab already exists for this mutasi
            $existingSertijab = Sertijab::where('id_mutasi', $request->id_mutasi)->first();
            
            if ($existingSertijab) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arsip untuk mutasi ini sudah ada!'
                ], 400);
            }

            $sertijabData = [
                'id_mutasi' => $request->id_mutasi,
                'catatan_admin' => $request->catatan_admin,
                'submitted_at' => now(),
                'status_dokumen' => 'draft'
            ];

            // Handle file uploads
            $uploadedFiles = [];
            $documentTypes = ['sertijab', 'familisasi', 'lampiran'];
            
            foreach ($documentTypes as $type) {
                $fileKey = "dokumen_{$type}";
                
                if ($request->hasFile($fileKey)) {
                    $file = $request->file($fileKey);
                    $filename = time() . "_{$type}_" . $file->getClientOriginalName();
                    $path = $file->storeAs("arsip/{$type}", $filename, 'public');
                    
                    $sertijabData["{$fileKey}_path"] = $path;
                    $sertijabData["status_{$type}"] = 'draft';
                    $uploadedFiles[] = $type;
                }
            }

            $sertijab = Sertijab::create($sertijabData);
            $sertijab->updateOverallStatus();

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Arsip berhasil ditambahkan!',
                    'uploaded_files' => $uploadedFiles
                ]);
            }

            return redirect()->route('arsip.index')
                ->with('success', 'Arsip berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error storing manual arsip: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan arsip: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan arsip: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update status untuk multiple arsip
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'sertijab_ids' => 'required|array',
            'sertijab_ids.*' => 'exists:sertijab_new,id',
            'status' => 'required|in:draft,partial,final',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'status_dokumen' => $request->status,
                'updated_at' => now(),
                'updated_by' => auth()->id()
            ];

            if ($request->notes) {
                $updateData['catatan_admin'] = $request->notes;
            }

            $updatedCount = Sertijab::whereIn('id', $request->sertijab_ids)
                ->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengupdate {$updatedCount} dokumen arsip",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in bulk update status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for dashboard widget
     */
    public function getStatistics()
    {
        try {
            $stats = $this->getArsipStatistics();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting arsip statistics: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik',
                'data' => $this->getDefaultStats()
            ], 500);
        }
    }

    /**
     * Get default statistics untuk fallback
     */
    private function getDefaultStats()
    {
        return [
            'total_arsip' => 0,
            'final_arsip' => 0,
            'draft_arsip' => 0,
            'pending_verification' => 0,
            'completion_rate' => 0,
            'rejected_documents' => 0
        ];
    }

    /**
     * Generate PDF laporan
     */
    private function generatePdfLaporan($arsipList, $filters)
    {
        // TODO: Implementation untuk PDF generation
        // Bisa menggunakan DomPDF atau library lainnya
        
        return response()->json([
            'success' => false,
            'message' => 'Fitur export PDF sedang dalam pengembangan',
            'data_count' => $arsipList->count(),
            'filters' => $filters
        ], 501); // Not Implemented
    }

    /**
     * Generate Excel laporan
     */
    private function generateExcelLaporan($arsipList, $filters)
    {
        // TODO: Implementation untuk Excel generation
        // Bisa menggunakan Laravel Excel atau library lainnya
        
        return response()->json([
            'success' => false,
            'message' => 'Fitur export Excel sedang dalam pengembangan',
            'data_count' => $arsipList->count(),
            'filters' => $filters
        ], 501); // Not Implemented
    }
}
