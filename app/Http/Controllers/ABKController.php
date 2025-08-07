<?php
// filepath: c:\laragon\www\SertijabPelni\app\Http\Controllers\ABKController.php

namespace App\Http\Controllers;

use App\Models\Kapal;
use App\Models\ABKNew;
use App\Models\Mutasi;
use App\Models\Jabatan;
use App\Imports\AbkImport;
use Illuminate\Http\Request;
use App\Imports\MutasiImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\RiwayatImportExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth; // TAMBAH INI

class ABKController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Data statistik ABK
            $totalStatistik = [
                'total_abk' => ABKNew::count(),
                'abk_aktif' => ABKNew::where('status_abk', '!=', 'Pensiun')->count(),
                'abk_organik' => ABKNew::where('status_abk', 'Organik')->count(),
                'abk_non_organik' => ABKNew::where('status_abk', 'Non Organik')->count(),
                'abk_pensiun' => ABKNew::where('status_abk', 'Pensiun')->count()
            ];

            // Data ABK per kapal - DARI TABEL MUTASI
            $abkPerKapal = Mutasi::with('kapal')
                ->select('id_kapal', 'nama_kapal')
                ->selectRaw('COUNT(DISTINCT id_abk_naik) as total_abk_naik')
                ->selectRaw('COUNT(DISTINCT id_abk_turun) as total_abk_turun')
                ->selectRaw('COUNT(CASE WHEN status_mutasi = "Selesai" THEN 1 END) as mutasi_selesai')
                ->selectRaw('COUNT(CASE WHEN status_mutasi IN ("Draft", "Disetujui") THEN 1 END) as mutasi_proses')
                ->groupBy('id_kapal', 'nama_kapal')
                ->orderByDesc('total_abk_naik')
                ->limit(6)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id_kapal,
                        'nama_kapal' => $item->nama_kapal,
                        'total_abk' => $item->total_abk_naik + $item->total_abk_turun,
                        'abk_aktif' => $item->mutasi_selesai,
                        'abk_tidak_aktif' => $item->mutasi_proses
                    ];
                });

            // Data mutasi terbaru
            $mutasiTerbaru = Mutasi::with(['kapal', 'abkNaik', 'abkTurun'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($mutasi) {
                    return [
                        'id' => $mutasi->id,
                        'abkTurun' => [
                            'nama_abk' => $mutasi->nama_lengkap_naik
                        ],
                        'kapalTurun' => [
                            'nama_kapal' => 'From Previous Ship'
                        ],
                        'kapalNaik' => [
                            'nama_kapal' => $mutasi->nama_kapal
                        ],
                        'status_mutasi' => $mutasi->status_mutasi,
                        'created_at' => $mutasi->created_at,
                        'jenis_mutasi' => $mutasi->jenis_mutasi,
                        'periode' => $mutasi->TMT ? $mutasi->TMT->format('d/m/Y') : 'N/A'
                    ];
                });

            // PERBAIKAN: ABK list dengan pagination dan search
            $search = $request->get('search', '');
            $perPage = (int) $request->get('per_page', 10);
            
            // Validasi per_page
            if (!in_array($perPage, [10, 25, 50, 100])) {
                $perPage = 10;
            }
            
            $abkQuery = ABKNew::with(['jabatanTetap']);
            
            // Apply search filter
            if (!empty($search)) {
                $searchTerm = '%' . $search . '%';
                $abkQuery->where(function($q) use ($searchTerm) {
                    $q->where('id', 'LIKE', $searchTerm)
                      ->orWhere('nama_abk', 'LIKE', $searchTerm)
                      ->orWhereHas('jabatanTetap', function($jq) use ($searchTerm) {
                          $jq->where('nama_jabatan', 'LIKE', $searchTerm);
                      })
                      ->orWhere('status_abk', 'LIKE', $searchTerm);
                });
            }
            
            $abkList = $abkQuery->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->withQueryString(); // Maintain search parameters in pagination links
                
            // PERBAIKAN: Jika request AJAX (untuk live search)
            if ($request->ajax()) {
                try {
                    $tableHtml = view('kelolaABK.partials.abk-table', compact('abkList', 'search'))->render();
                    $paginationHtml = view('kelolaABK.partials.pagination', compact('abkList', 'search'))->render();
                    
                    return response()->json([
                        'success' => true,
                        'html' => $tableHtml,
                        'pagination' => $paginationHtml,
                        'total' => $abkList->total(),
                        'current_page' => $abkList->currentPage(),
                        'last_page' => $abkList->lastPage(),
                        'per_page' => $abkList->perPage(),
                        'from' => $abkList->firstItem(),
                        'to' => $abkList->lastItem(),
                        'search' => $search
                    ], 200, ['Content-Type' => 'application/json']);
                    
                } catch (\Exception $e) {
                    Log::error('AJAX Error in ABK index: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Terjadi kesalahan saat memuat data',
                        'error' => $e->getMessage()
                    ], 500, ['Content-Type' => 'application/json']);
                }
            }

            Log::info('ABK data loaded successfully', [
                'total_abk' => $totalStatistik['total_abk'],
                'kapal_count' => $abkPerKapal->count(),
                'mutasi_count' => $mutasiTerbaru->count(),
                'abk_list_count' => $abkList->total(),
                'search' => $search
            ]);
                
            return view('kelolaABK.index', compact(
                'abkList',
                'totalStatistik', 
                'abkPerKapal', 
                'mutasiTerbaru',
                'search'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading ABK index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Jika AJAX request, return JSON error
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
                ], 500, ['Content-Type' => 'application/json']);
            }
            
            // Fallback data jika error
            $totalStatistik = [
                'total_abk' => 0,
                'abk_aktif' => 0,
                'abk_organik' => 0,
                'abk_non_organik' => 0,
                'abk_pensiun' => 0
            ];
            
            $abkPerKapal = collect();
            $mutasiTerbaru = collect();
            $abkList = ABKNew::paginate(10);
            $search = '';
            
            return view('kelolaABK.index', compact(
                'abkList',
                'totalStatistik', 
                'abkPerKapal', 
                'mutasiTerbaru',
                'search'
            ))->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $daftarJabatan = Jabatan::orderBy('nama_jabatan', 'asc')->get();
            
            return view('kelolaABK.create', compact('daftarJabatan'));
        } catch (\Exception $e) {
            Log::error('Error loading create ABK form: ' . $e->getMessage());
            return redirect()->route('abk.index')->with('error', 'Gagal memuat form tambah ABK');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log request untuk debugging
        Log::info('ABK Store Request:', $request->all());
        
        try {
            // Validasi input
            $validatedData = $request->validate([
                'id' => [
                    'required',
                    'string',
                    'min:4',
                    'max:20',
                    'regex:/^[0-9]+$/',
                    'unique:abk_new,id'
                ],
                'nama_abk' => 'required|string|max:255|min:2',
                'id_jabatan_tetap' => 'required|exists:jabatan,id',
                'status_abk' => 'required|in:Organik,Non Organik'
            ], [
                'id.required' => 'NRP/ID ABK wajib diisi',
                'id.min' => 'NRP minimal 4 karakter',
                'id.max' => 'NRP maksimal 20 karakter',
                'id.regex' => 'NRP hanya boleh berisi angka',
                'id.unique' => 'NRP sudah terdaftar dalam sistem',
                'nama_abk.required' => 'Nama ABK wajib diisi',
                'nama_abk.min' => 'Nama ABK minimal 2 karakter',
                'id_jabatan_tetap.required' => 'Jabatan tetap wajib dipilih',
                'id_jabatan_tetap.exists' => 'Jabatan yang dipilih tidak valid',
                'status_abk.required' => 'Status ABK wajib dipilih',
                'status_abk.in' => 'Status ABK tidak valid'
            ]);

            DB::beginTransaction();

            // Cek apakah jabatan ada
            $jabatan = Jabatan::findOrFail($validatedData['id_jabatan_tetap']);

            // Create ABK baru
            $abk = ABKNew::create([
                'id' => $validatedData['id'],
                'nama_abk' => $validatedData['nama_abk'],
                'id_jabatan_tetap' => $validatedData['id_jabatan_tetap'],
                'status_abk' => $validatedData['status_abk']
            ]);

            DB::commit();

            Log::info('ABK created successfully:', ['id' => $abk->id]);

            // Response untuk AJAX request
            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Data ABK berhasil ditambahkan',
                    'data' => [
                        'id' => $abk->id,
                        'nama_abk' => $abk->nama_abk,
                        'jabatan' => $jabatan->nama_jabatan,
                        'status' => $abk->status_abk
                    ]
                ], 200);
            }

            // Fallback untuk non-AJAX
            return redirect()->route('abk.index')->with('success', 'Data ABK berhasil ditambahkan');

        } catch (ValidationException $e) {
            DB::rollback();
            
            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withInput()->withErrors($e->errors());
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error storing ABK: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data ABK: ' . $e->getMessage()]);
        }
    }

    /**
     * AJAX endpoint untuk cek NRP availability
     */
    public function checkNRP(Request $request)
    {
        try {
            $nrp = $request->get('nrp');
            $excludeId = $request->get('exclude_id'); // ID yang dikecualikan untuk edit
            
            Log::info('Check NRP request:', ['nrp' => $nrp, 'exclude_id' => $excludeId]);
            
            if (!$nrp) {
                return response()->json([
                    'available' => false,
                    'message' => 'NRP tidak boleh kosong'
                ]);
            }
            
            // Validasi format
            if (!preg_match('/^[0-9]{4,20}$/', $nrp)) {
                return response()->json([
                    'available' => false,
                    'message' => 'Format NRP tidak valid. Gunakan angka saja, 4-20 karakter'
                ]);
            }
            
            // Cek ketersediaan dengan exclude ID untuk edit
            $query = ABKNew::where('id', $nrp);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            $exists = $query->exists();
            
            return response()->json([
                'available' => !$exists,
                'message' => $exists ? 'NRP sudah terdaftar dalam sistem' : 'NRP tersedia'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error checking NRP: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'message' => 'Terjadi kesalahan saat validasi NRP'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Load ABK dengan relasi yang diperlukan
            $abk = ABKNew::with([
                'jabatanTetap',
                'kapalAktif'
            ])->findOrFail($id);

            // Get mutasi data untuk ABK ini (baik sebagai naik maupun turun)
            $mutasiSebagaiNaik = Mutasi::with([
                'kapal', 
                'jabatanMutasi', 
                'abkTurun', 
                'jabatanTetapTurun',
                'sertijab' // Load sertijab relation
            ])
            ->where('id_abk_naik', $id)
            ->orderBy('TMT', 'desc')
            ->get();

            $mutasiSebagaiTurun = Mutasi::with([
                'kapal', 
                'abkNaik', 
                'jabatanTetapNaik',
                'jabatanMutasiTurun',
                'sertijab'
            ])
            ->where('id_abk_turun', $id)
            ->orderBy('TMT_turun', 'desc')
            ->get();

            // Gabungkan semua mutasi dan urutkan berdasarkan tanggal
            $semuaMutasi = collect();
            
            // Add mutasi sebagai naik
            foreach ($mutasiSebagaiNaik as $mutasi) {
                $semuaMutasi->push([
                    'id' => $mutasi->id,
                    'type' => 'naik',
                    'tanggal' => $mutasi->TMT,
                    'tanggal_akhir' => $mutasi->TAT,
                    'kapal' => $mutasi->kapal->nama_kapal ?? $mutasi->nama_kapal,
                    'jabatan' => $mutasi->nama_mutasi ?: ($mutasi->jabatanMutasi->nama_jabatan ?? 'N/A'),
                    'jenis_mutasi' => $mutasi->jenis_mutasi,
                    'status' => $mutasi->status_mutasi,
                    'catatan' => $mutasi->catatan,
                    'abk_pasangan' => $mutasi->abkTurun ? [
                        'id' => $mutasi->abkTurun->id,
                        'nama' => $mutasi->nama_lengkap_turun ?: $mutasi->abkTurun->nama_abk,
                        'jabatan' => $mutasi->nama_mutasi_turun ?: ($mutasi->jabatanMutasiTurun->nama_jabatan ?? 'N/A')
                    ] : null,
                    'sertijab' => $mutasi->sertijab,
                    'raw_mutasi' => $mutasi
                ]);
            }

            // Add mutasi sebagai turun
            foreach ($mutasiSebagaiTurun as $mutasi) {
                $semuaMutasi->push([
                    'id' => $mutasi->id,
                    'type' => 'turun',
                    'tanggal' => $mutasi->TMT_turun ?: $mutasi->TMT,
                    'tanggal_akhir' => $mutasi->TAT_turun ?: $mutasi->TAT,
                    'kapal' => $mutasi->kapal->nama_kapal ?? $mutasi->nama_kapal,
                    'jabatan' => $mutasi->nama_mutasi_turun ?: ($mutasi->jabatanMutasiTurun->nama_jabatan ?? 'N/A'),
                    'jenis_mutasi' => $mutasi->jenis_mutasi_turun ?: $mutasi->jenis_mutasi,
                    'status' => $mutasi->status_mutasi,
                    'catatan' => $mutasi->keterangan_turun ?: $mutasi->catatan,
                    'abk_pasangan' => [
                        'id' => $mutasi->abkNaik->id,
                        'nama' => $mutasi->nama_lengkap_naik ?: $mutasi->abkNaik->nama_abk,
                        'jabatan' => $mutasi->nama_mutasi ?: ($mutasi->jabatanMutasi->nama_jabatan ?? 'N/A')
                    ],
                    'sertijab' => $mutasi->sertijab,
                    'raw_mutasi' => $mutasi
                ]);
            }

            // Sort by tanggal descending
            $riwayatMutasi = $semuaMutasi->sortByDesc('tanggal')->values();

            // Get current assignment (mutasi aktif/terbaru)
            $mutasiAktif = $riwayatMutasi->where('status', '!=', 'Selesai')->first() 
                        ?? $riwayatMutasi->first();

            // Statistics
            $statistik = [
                'total_mutasi' => $riwayatMutasi->count(),
                'mutasi_naik' => $riwayatMutasi->where('type', 'naik')->count(),
                'mutasi_turun' => $riwayatMutasi->where('type', 'turun')->count(),
                'mutasi_aktif' => $riwayatMutasi->where('status', '!=', 'Selesai')->count(),
                'mutasi_selesai' => $riwayatMutasi->where('status', 'Selesai')->count(),
                'sertijab_count' => $riwayatMutasi->whereNotNull('sertijab')->count(),
                'kapal_berbeda' => $riwayatMutasi->pluck('kapal')->unique()->count()
            ];

            // Recent sertijab documents
            $dokumenSertijab = collect();
            foreach ($riwayatMutasi->whereNotNull('sertijab') as $mutasi) {
                if ($mutasi['sertijab']) {
                    $dokumenSertijab->push([
                        'mutasi_id' => $mutasi['id'],
                        'kapal' => $mutasi['kapal'],
                        'tanggal' => $mutasi['tanggal'],
                        'sertijab' => $mutasi['sertijab'],
                        'type' => $mutasi['type']
                    ]);
                }
            }

            Log::info('ABK show data loaded', [
                'abk_id' => $id,
                'mutasi_count' => $riwayatMutasi->count(),
                'sertijab_count' => $dokumenSertijab->count()
            ]);

            return view('kelolaABK.show', compact(
                'abk', 
                'riwayatMutasi', 
                'mutasiAktif',
                'statistik',
                'dokumenSertijab'
            ));

        } catch (\Exception $e) {
            Log::error('Error showing ABK: ' . $e->getMessage(), [
                'abk_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('abk.index')->with('error', 'ABK tidak ditemukan atau terjadi kesalahan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $abk = ABKNew::with('jabatanTetap')->findOrFail($id);
            $daftarJabatan = Jabatan::orderBy('nama_jabatan', 'asc')->get();
            
            return view('kelolaABK.edit', compact('abk', 'daftarJabatan'));
        } catch (\Exception $e) {
            Log::error('Error loading edit ABK form: ' . $e->getMessage());
            return redirect()->route('abk.index')->with('error', 'Gagal memuat form edit ABK');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $abk = ABKNew::findOrFail($id);
            
            $validatedData = $request->validate([
                'id' => [
                    'required',
                    'string',
                    'min:4',
                    'max:20',
                    'regex:/^[0-9]+$/',
                    'unique:abk_new,id,' . $abk->id . ',id'
                ],
                'nama_abk' => 'required|string|max:255|min:2',
                'id_jabatan_tetap' => 'required|exists:jabatan,id',
                'status_abk' => 'required|in:Organik,Non Organik,Pensiun'
            ]);

            DB::beginTransaction();

            $abk->update($validatedData);

            DB::commit();

            return redirect()->route('abk.index')->with('success', 'Data ABK berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating ABK: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui data ABK']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $abk = ABKNew::findOrFail($id);
            $namaABK = $abk->nama_abk;
            
            // Cek apakah ABK masih memiliki mutasi aktif
            // Uncomment jika ada relasi mutasi
            /*
            if ($abk->mutasiAktif()->exists()) {
                return redirect()->back()->with('error', 'ABK tidak dapat dihapus karena masih memiliki mutasi aktif.');
            }
            */
            
            $abk->delete();
            
            return redirect()->route('abk.index')->with('success', "ABK {$namaABK} berhasil dihapus dari sistem.");
            
        } catch (\Exception $e) {
            Log::error('Error deleting ABK: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus ABK: ' . $e->getMessage());
        }
    }

    /**
     * Show export import page - UPDATE method ini
     */
    public function exportPage()
    {
        try {
            // Get data for filters
            $daftarKapal = Kapal::select('id as id_kapal', 'nama_kapal')
                ->orderBy('nama_kapal')
                ->get();
            
            // Export statistics
            $exportStats = [
                'total_abk' => ABKNew::count(),
                'aktif' => ABKNew::where('status_abk', '!=', 'Pensiun')->count(),
                'kapal' => $daftarKapal->count()
            ];
            
            // Import/Export history dengan limit 10 dan data yang lebih lengkap
            $importHistory = RiwayatImportExport::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10) // Batasi hanya 10 record terbaru
                ->get()
                ->map(function($history) {
                    return (object)[
                        'id' => $history->id,
                        'file_name' => $history->nama_file,
                        'import_type' => $history->jenis,
                        'tipe' => $history->tipe,
                        'jenis' => $history->jenis,
                        'status' => $history->status,
                        'processed_records' => $history->jumlah_berhasil,
                        'total_records' => $history->jumlah_data,
                        'failed_records' => $history->jumlah_gagal,
                        'jumlah_dilewati' => $history->jumlah_dilewati ?? 0,
                        'admin_name' => $history->user->nama_admin ?? 'System',
                        'created_at' => $history->created_at,
                        'error_log' => $history->keterangan,
                        'file_size' => $history->file_size,
                        'durasi_proses' => $history->durasi_proses,
                        
                        // Computed attributes dari model
                        'formatted_file_size' => $history->formatted_file_size,
                        'formatted_duration' => $history->formatted_duration,
                        'success_rate' => $history->success_rate,
                        'status_badge_class' => $history->status_badge_class,
                        'status_icon' => $history->status_icon,
                        'tipe_icon' => $history->tipe_icon,
                        'jenis_label' => $history->jenis_label
                    ];
                });
            
            return view('kelolaABK.export', compact(
                'daftarKapal',
                'exportStats', 
                'importHistory'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading export page: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('abk.index')->with('error', 'Gagal memuat halaman export: ' . $e->getMessage());
        }
    }

    /**
     * Export ABK data - UPDATE dengan riwayat
     */
    public function export(Request $request, $format = 'excel')
    {
        try {
            Log::info('Export request:', [
                'format' => $format,
                'params' => $request->all(),
                'user_id' => Auth::id()
            ]);

            // Build query with filters
            $query = ABKNew::with(['jabatanTetap']);
            
            // Apply filters
            if ($request->export_kapal) {
                $query->where('id_kapal_aktif', $request->export_kapal);
            }
            
            if ($request->export_status) {
                $query->where('status_abk', $request->export_status);
            }
            
            if ($request->export_start_date) {
                $query->whereDate('created_at', '>=', $request->export_start_date);
            }
            
            if ($request->export_end_date) {
                $query->whereDate('created_at', '<=', $request->export_end_date);
            }
            
            $abkData = $query->orderBy('nama_abk')->get();
            $totalRecords = $abkData->count();
            
            // Generate filename
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "data_abk_{$format}_{$timestamp}.{$format}";
            
            // Simpan riwayat export ke database
            $riwayat = RiwayatImportExport::create([
                'nama_file' => $filename,
                'tipe' => 'export',
                'jenis' => 'abk',
                'status' => 'processing',
                'jumlah_data' => $totalRecords,
                'jumlah_berhasil' => 0,
                'jumlah_gagal' => 0,
                'keterangan' => 'Export dimulai',
                'user_id' => Auth::id()
            ]);
            
            try {
                if ($format === 'excel') {
                    $response = $this->exportExcel($abkData, $request, $filename);
                } elseif ($format === 'pdf') {
                    $response = $this->exportPdf($abkData, $request, $filename);
                } else {
                    throw new \Exception('Format tidak didukung');
                }
                
                // Update riwayat sukses
                $riwayat->update([
                    'status' => 'success',
                    'jumlah_berhasil' => $totalRecords,
                    'keterangan' => "Export berhasil. {$totalRecords} data diekspor."
                ]);
                
                return $response;
                
            } catch (\Exception $e) {
                // Update riwayat gagal
                $riwayat->update([
                    'status' => 'failed',
                    'keterangan' => 'Export gagal: ' . $e->getMessage()
                ]);
                
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal export data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export to Excel - UPDATE dengan filename parameter
     */
    private function exportExcel($abkData, $request, $filename = null)
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set title dan info
            $sheet->setCellValue('A1', 'LAPORAN DATA ABK');
            $sheet->setCellValue('A2', 'Tanggal Export: ' . date('d/m/Y H:i:s'));
            $sheet->setCellValue('A3', 'Total Data: ' . $abkData->count() . ' ABK');
            
            // Merge title cells
            $sheet->mergeCells('A1:F1');
            $sheet->mergeCells('A2:F2');
            $sheet->mergeCells('A3:F3');
            
            // Style title
            $sheet->getStyle('A1:A3')->getFont()->setBold(true);
            $sheet->getStyle('A1')->getFont()->setSize(16);
            $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            // Set headers
            $headers = [
                'A5' => 'NRP',
                'B5' => 'Nama ABK',
                'C5' => 'Jabatan Tetap',
                'D5' => 'Status ABK',
                'E5' => 'Kapal Aktif',
                'F5' => 'Tanggal Dibuat'
            ];
            
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }
            
            // Style headers
            $sheet->getStyle('A5:F5')->getFont()->setBold(true);
            $sheet->getStyle('A5:F5')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E3F2FD');
            $sheet->getStyle('A5:F5')->getBorders()->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            // Add data
            $row = 6;
            foreach ($abkData as $abk) {
                $sheet->setCellValue('A' . $row, $abk->id);
                $sheet->setCellValue('B' . $row, $abk->nama_abk);
                $sheet->setCellValue('C' . $row, $abk->jabatanTetap->nama_jabatan ?? '-');
                $sheet->setCellValue('D' . $row, $abk->status_abk);
                $sheet->setCellValue('E' . $row, 'N/A'); // Kapal aktif field tidak ada di model
                $sheet->setCellValue('F' . $row, $abk->created_at ? $abk->created_at->format('d/m/Y') : '-');
                
                // Add borders to data rows
                $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                
                $row++;
            }
            
            // Auto-size columns
            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Set default filename if not provided
            if (!$filename) {
                $filename = 'data_abk_' . date('Y-m-d_H-i-s') . '.xlsx';
            }
            
            // Create writer and save to temp file
            $writer = new Xlsx($spreadsheet);
            $tempFile = tempnam(sys_get_temp_dir(), 'abk_export_');
            $writer->save($tempFile);
            
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Excel export error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Export to PDF - UPDATE dengan filename parameter
     */
    private function exportPdf($abkData, $request, $filename = null)
    {
        try {
            $data = [
                'abkData' => $abkData,
                'filters' => $request->all(),
                'generated_at' => now(),
                'total_records' => $abkData->count(),
                'exported_by' => Auth::user()->name ?? 'System'
            ];
            
            $pdf = PDF::loadView('kelolaABK.export-pdf', $data);
            
            if (!$filename) {
                $filename = 'data_abk_' . date('Y-m-d_H-i-s') . '.pdf';
            }
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('PDF export error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Download Excel template - UPDATE: Perbaiki error instruksi
     */
    public function downloadTemplate($type = 'excel')
    {
        try {
            // Hanya support Excel
            if ($type !== 'excel') {
                return response()->json(['error' => 'Format tidak didukung. Hanya Excel yang tersedia.'], 400);
            }
            
            $spreadsheet = new Spreadsheet();
            
            // Create Mutasi Template Sheet (Sheet utama)
            $mutasiSheet = $spreadsheet->getActiveSheet();
            $mutasiSheet->setTitle('Template Mutasi');
            
            // Headers sesuai dengan MutasiImport.php
            $mutasiHeaders = [
                'A1' => 'nama_kapal',
                'B1' => 'id_abk_naik',
                'C1' => 'id_jabatan_mutasi',
                'D1' => 'nama_mutasi',
                'E1' => 'jenis_mutasi',
                'F1' => 'tmt',
                'G1' => 'tat',
                'H1' => 'id_abk_turun',
                'I1' => 'nama_mutasi_turun',
                'J1' => 'jenis_mutasi_turun',
                'K1' => 'id_jabatan_mutasi_turun',
                'L1' => 'tmt_turun',
                'M1' => 'tat_turun',
                'N1' => 'catatan'
            ];

            foreach ($mutasiHeaders as $cell => $value) {
                $mutasiSheet->setCellValue($cell, $value);
            }

            // Style headers
            $mutasiSheet->getStyle('A1:N1')->getFont()->setBold(true);
            $mutasiSheet->getStyle('A1:N1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E3F2FD');
            $mutasiSheet->getStyle('A1:N1')->getBorders()->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Add Mutasi example data
            $mutasiExampleData = [
                [
                    'KM KELIMUTU',   // nama_kapal
                    '12345',                    // id_abk_naik
                    '1',                        // id_jabatan_mutasi
                    'PC',                  // nama_mutasi
                    'Sementara',               // jenis_mutasi
                    '09/11/2025',              // tmt
                    '09/05/2026',              // tat
                    '67890',                    // id_abk_turun
                    'PC',                  // nama_mutasi_turun
                    'Sementara',               // jenis_mutasi_turun
                    '1',                        // id_jabatan_mutasi_turun
                    '09/11/2025',              // tmt_turun
                    '09/05/2026',              // tat_turun
                    'Mutasi rutin sesuai rotasi' // catatan
                ],
                [
                    'KM SIRIMAU',
                    '23456',
                    '2',
                    'TOD',
                    'Definitif',
                    '15/12/2025',
                    '',                         // tat kosong untuk definitif
                    '',                         // id_abk_turun kosong
                    '',                         // nama_mutasi_turun kosong
                    '',                         // jenis_mutasi_turun kosong
                    '',                         // id_jabatan_mutasi_turun kosong
                    '',                         // tmt_turun kosong
                    '',                         // tat_turun kosong
                    'Promosi jabatan'
                ],
                [
                    'KM SIRIMAU',
                    '34567',
                    '3',
                    'PC',
                    'Sementara',
                    '01/01/2026',
                    '01/07/2026',
                    '78901',                    // id_abk_turun ada
                    'PC',                // nama_mutasi_turun
                    'Sementara',               // jenis_mutasi_turun
                    '3',                        // id_jabatan_mutasi_turun sama
                    '01/01/2026',              // tmt_turun sama
                    '01/07/2026',              // tat_turun sama
                    'Penempatan baru dengan pengganti'
                ]
            ];

            $row = 2;
            foreach ($mutasiExampleData as $data) {
                $col = 'A';
                foreach ($data as $value) {
                    $mutasiSheet->setCellValue($col . $row, $value);
                    $col++;
                }
                
                // Add borders to data rows
                $mutasiSheet->getStyle("A{$row}:N{$row}")->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'N') as $col) {
                $mutasiSheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Create ABK Template Sheet (Sheet kedua)
            $abkSheet = $spreadsheet->createSheet();
            $abkSheet->setTitle('Template ABK');
            
            // ABK Template headers
            $abkHeaders = [
                'A1' => 'id',
                'B1' => 'nama_abk', 
                'C1' => 'id_jabatan_tetap',
                'D1' => 'status_abk'
            ];
            
            foreach ($abkHeaders as $cell => $value) {
                $abkSheet->setCellValue($cell, $value);
            }
            
            // Style ABK headers
            $abkSheet->getStyle('A1:D1')->getFont()->setBold(true);
            $abkSheet->getStyle('A1:D1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E8F5E8');
            $abkSheet->getStyle('A1:D1')->getBorders()->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            // Add ABK example data
            $abkExampleData = [
                ['12345', 'John Doe', '1', 'Organik'],
                ['23456', 'Ahmad Ali', '2', 'Non Organik'],
                ['34567', 'Budi Santoso', '3', 'Organik'],
                ['67890', 'Siti Nurhaliza', '1', 'Organik'],
                ['78901', 'Rizki Pratama', '3', 'Non Organik']
            ];
            
            $row = 2;
            foreach ($abkExampleData as $data) {
                $col = 'A';
                foreach ($data as $value) {
                    $abkSheet->setCellValue($col . $row, $value);
                    $col++;
                }
                
                // Add borders to data rows
                $abkSheet->getStyle("A{$row}:D{$row}")->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                
                $row++;
            }
            
            // Auto-size ABK columns
            foreach (range('A', 'D') as $col) {
                $abkSheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Create Instructions Sheet - PERBAIKAN: Hindari karakter khusus
            $instructionSheet = $spreadsheet->createSheet();
            $instructionSheet->setTitle('Instruksi');
            
            // PERBAIKAN: Gunakan array simple tanpa karakter khusus yang bermasalah
            $instructions = [
                'INSTRUKSI IMPORT DATA MUTASI DAN ABK',
                '',
                '--- TEMPLATE MUTASI (Sheet 1) ---',
                '1. Kolom yang WAJIB diisi:',
                '   - nama_kapal: Nama kapal tujuan mutasi',
                '   - id_abk_naik: ID/NRP ABK yang naik ke kapal (harus sudah ada di database)',
                '   - tmt: Tanggal mulai tugas (format: dd/mm/yyyy)',
                '',
                '2. Kolom yang OPSIONAL:',
                '   - id_jabatan_mutasi: ID jabatan dari database (angka)',
                '   - nama_mutasi: Nama jabatan yang akan diisi',
                '   - jenis_mutasi: Sementara atau Definitif (default: Sementara)',
                '   - tat: Tanggal akhir tugas (kosong jika definitif)',
                '   - id_abk_turun: ID ABK yang diganti (jika ada)',
                '   - nama_mutasi_turun: Jabatan ABK yang turun',
                '   - jenis_mutasi_turun: Jenis mutasi ABK turun',
                '   - id_jabatan_mutasi_turun: ID jabatan khusus untuk ABK turun',
                '   - tmt_turun: TMT khusus untuk ABK turun (bisa berbeda)',
                '   - tat_turun: TAT khusus untuk ABK turun (bisa berbeda)',
                '   - catatan: Keterangan tambahan',
                '',
                '--- TEMPLATE ABK (Sheet 2) ---',
                '1. Kolom yang WAJIB diisi:',
                '   - id: Nomor Registrasi Pokok/NRP (hanya angka, 4-20 digit)',
                '   - nama_abk: Nama lengkap ABK',
                '   - id_jabatan_tetap: ID jabatan dari database (angka)',
                '',
                '2. Kolom yang OPSIONAL:',
                '   - status_abk: Organik, Non Organik, atau Pensiun (default: Organik)',
                '',
                '--- FORMAT DATA ---',
                '1. Format tanggal: dd/mm/yyyy (contoh: 09/11/2025)',
                '2. Format ID/NRP: Hanya angka, 4-20 karakter',
                '3. Gunakan format Excel (.xlsx atau .xls)',
                '4. Maksimal 1000 baris data per sheet',
                '',
                '--- CATATAN PENTING ---',
                '1. Hapus baris contoh sebelum input data sesungguhnya',
                '2. Pastikan ABK sudah terdaftar di database sebelum import mutasi',
                '3. Nama kapal harus sesuai dengan data di sistem',
                '4. ID jabatan harus sesuai dengan database (lihat master jabatan)',
                '5. Untuk mutasi definitif, kosongkan field TAT',
                '6. Untuk mutasi tanpa ABK turun, kosongkan semua field *_turun',
                '7. Field TMT/TAT turun opsional, jika kosong akan menggunakan TMT/TAT naik',
                '',
                '--- CONTOH PENGISIAN ---',
                'Mutasi dengan pengganti: Isi semua field termasuk id_abk_turun',
                'Mutasi tanpa pengganti: Kosongkan field yang berkaitan dengan turun',
                'Mutasi definitif: Kosongkan field TAT dan TAT_turun',
                'Mutasi dengan waktu berbeda: Isi TMT_turun dan TAT_turun yang berbeda',
                '',
                'Jika ada pertanyaan, hubungi administrator sistem.'
            ];
            
            $row = 1;
            foreach ($instructions as $instruction) {
                // PERBAIKAN: Set nilai langsung tanpa array wrapping
                $instructionSheet->setCellValue('A' . $row, $instruction);
                
                // Style different sections
                if ($row === 1) {
                    $instructionSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                } elseif (strpos($instruction, '--- ') === 0) {
                    $instructionSheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
                } elseif (strpos($instruction, '   -') === 0) {
                    // Bullet points
                    $instructionSheet->getStyle('A' . $row)->getFont()->setSize(10);
                } elseif (preg_match('/^\d+\./', $instruction)) {
                    // Numbered items
                    $instructionSheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(11);
                }
                
                $row++;
            }
            
            // PERBAIKAN: Set column width yang lebih reasonable
            $instructionSheet->getColumnDimension('A')->setWidth(80);
            
            // Set active sheet back to Mutasi template
            $spreadsheet->setActiveSheetIndex(0);
            
            $filename = 'template_import_mutasi_abk_' . date('Y-m-d') . '.xlsx';
            
            // Create writer
            $writer = new Xlsx($spreadsheet);
            
            // Set proper headers for download
            $tempFile = tempnam(sys_get_temp_dir(), 'template_');
            $writer->save($tempFile);
            
            Log::info('Template Excel generated successfully', [
                'filename' => $filename,
                'sheets' => ['Template Mutasi', 'Template ABK', 'Instruksi'],
                'temp_file' => $tempFile
            ]);
            
            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Template download error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return response()->json([
                'error' => 'Gagal download template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import data from file
     */
    public function import(Request $request)
{
    try {
        // Validasi request dengan handling khusus untuk boolean
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'import_type' => 'required|in:mutasi,abk',
            'skip_duplicates' => 'nullable' // Hapus boolean validation, handle manual
        ]);
        
        // PERBAIKAN: Handle skip_duplicates dengan lebih fleksibel
        $skipDuplicates = $this->parseBooleanValue($request->get('skip_duplicates', true));
        
        Log::info('Import request received:', [
            'type' => $request->import_type,
            'file' => $request->file('import_file')->getClientOriginalName(),
            'skip_duplicates_raw' => $request->get('skip_duplicates'),
            'skip_duplicates_parsed' => $skipDuplicates
        ]);
        
        $file = $request->file('import_file');
        
        if ($request->import_type === 'abk') {
            return $this->importABKWithClass($file, $skipDuplicates);
        } else {
            return $this->importMutasi($file, $skipDuplicates);
        }
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation error in import:', $e->errors());
        
        // Gunakan Laravel Collection untuk flatten
        $allErrors = collect($e->errors())->flatten()->toArray();
        
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal: ' . implode(', ', $allErrors),
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        Log::error('Import error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Gagal import data: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Helper method untuk parse boolean value dari request
 */
private function parseBooleanValue($value)
{
    // Handle berbagai format boolean
    if (is_bool($value)) {
        return $value;
    }
    
    if (is_string($value)) {
        $value = strtolower(trim($value));
        return in_array($value, ['1', 'true', 'on', 'yes']);
    }
    
    if (is_numeric($value)) {
        return (bool) $value;
    }
    
    // Default ke true jika tidak ada atau null
    return $value !== null ? (bool) $value : true;
}

/**
 * Import ABK data using AbkImport class
 */
private function importABKWithClass($file, $skipDuplicates)
{
    try {
        $originalFilename = $file->getClientOriginalName();
        
        // Simpan riwayat import ke database
        $riwayat = RiwayatImportExport::create([
            'nama_file' => $originalFilename,
            'tipe' => 'import',
            'jenis' => 'abk',
            'status' => 'processing',
            'jumlah_data' => 0,
            'jumlah_berhasil' => 0,
            'jumlah_gagal' => 0,
            'keterangan' => 'Import dimulai',
            'user_id' => Auth::id()
        ]);
        
        DB::beginTransaction();
        
        // Create import instance
        $import = new \App\Imports\AbkImport($skipDuplicates);
        
        // Import the file
        Excel::import($import, $file);
        
        // Get statistics
        $stats = $import->getStats();
        
        DB::commit();
        
        $totalProcessed = $stats['imported'] + $stats['skipped'] + $stats['failed'];
        
        // Prepare response message
        $message = "Import selesai. ";
        $message .= "{$stats['imported']} data berhasil diimport";
        
        if ($stats['skipped'] > 0) {
            $message .= ", {$stats['skipped']} data dilewati";
        }
        
        if ($stats['failed'] > 0) {
            $message .= ", {$stats['failed']} data gagal";
        }
        
        // Update riwayat import
        $status = $stats['failed'] > 0 ? 'warning' : 'success';
        $keterangan = $message;
        
        if (!empty($stats['errors'])) {
            $keterangan .= "\n\nDetail Error:\n" . implode("\n", array_slice($stats['errors'], 0, 5));
            if (count($stats['errors']) > 5) {
                $keterangan .= "\n... dan " . (count($stats['errors']) - 5) . " error lainnya";
            }
        }
        
        $riwayat->update([
            'status' => $status,
            'jumlah_data' => $totalProcessed,
            'jumlah_berhasil' => $stats['imported'],
            'jumlah_gagal' => $stats['failed'],
            'keterangan' => $keterangan
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'total_records' => $totalProcessed,
            'success_records' => $stats['imported'],
            'skipped_records' => $stats['skipped'],
            'failed_records' => $stats['failed'],
            'errors' => $stats['errors'],
            'riwayat_id' => $riwayat->id
        ]);
        
    } catch (\Exception $e) {
        DB::rollback();
        
        // Update riwayat jika ada error
        if (isset($riwayat)) {
            $riwayat->update([
                'status' => 'failed',
                'keterangan' => 'Import gagal: ' . $e->getMessage()
            ]);
        }
        
        Log::error('Import ABK with class error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal import data ABK: ' . $e->getMessage()
        ], 500);
    }
}


public function getRiwayatHistory(Request $request)
{
    try {
        $limit = $request->get('limit', 10);
        
        $riwayat = RiwayatImportExport::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($history) {
                return [
                    'id' => $history->id,
                    'nama_file' => $history->nama_file,
                    'tipe' => $history->tipe,
                    'jenis' => $history->jenis,
                    'status' => $history->status,
                    'jumlah_data' => $history->jumlah_data,
                    'jumlah_berhasil' => $history->jumlah_berhasil,
                    'jumlah_gagal' => $history->jumlah_gagal,
                    'jumlah_dilewati' => $history->jumlah_dilewati ?? 0,
                    'admin_name' => $history->user->name ?? 'System',
                    'created_at' => $history->created_at->toISOString(),
                    'keterangan' => $history->keterangan,
                    'file_size' => $history->file_size,
                    'durasi_proses' => $history->durasi_proses,
                    
                    // Computed fields
                    'formatted_file_size' => $history->formatted_file_size,
                    'formatted_duration' => $history->formatted_duration,
                    'success_rate' => $history->success_rate
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $riwayat
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error getting riwayat history: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat riwayat'
        ], 500);
    }
}

/**
 * Import mutasi data - Updated
 */
private function importMutasi($file, $skipDuplicates)
{
    try {
        $originalFilename = $file->getClientOriginalName();
        
        // Simpan riwayat import ke database
        $riwayat = RiwayatImportExport::create([
            'nama_file' => $originalFilename,
            'tipe' => 'import',
            'jenis' => 'mutasi',
            'status' => 'processing',
            'jumlah_data' => 0,
            'jumlah_berhasil' => 0,
            'jumlah_gagal' => 0,
            'keterangan' => 'Import mutasi dimulai',
            'user_id' => Auth::id()
        ]);
        
        DB::beginTransaction();
        
        // Create import instance
        $import = new MutasiImport($skipDuplicates);
        
        // Import the file
        Excel::import($import, $file);
        
        // Get statistics
        $stats = $import->getStats();
        
        DB::commit();
        
        $totalProcessed = $stats['imported'] + $stats['skipped'] + $stats['failed'];
        
        // Prepare response message
        $message = "Import mutasi selesai. ";
        $message .= "{$stats['imported']} data berhasil diimport";
        
        if ($stats['skipped'] > 0) {
            $message .= ", {$stats['skipped']} data dilewati";
        }
        
        if ($stats['failed'] > 0) {
            $message .= ", {$stats['failed']} data gagal";
        }
        
        // Update riwayat import
        $status = $stats['failed'] > 0 ? 'warning' : 'success';
        $keterangan = $message;
        
        if (!empty($stats['errors'])) {
            $keterangan .= "\n\nDetail Error:\n" . implode("\n", array_slice($stats['errors'], 0, 5));
            if (count($stats['errors']) > 5) {
                $keterangan .= "\n... dan " . (count($stats['errors']) - 5) . " error lainnya";
            }
        }
        
        $riwayat->update([
            'status' => $status,
            'jumlah_data' => $totalProcessed,
            'jumlah_berhasil' => $stats['imported'],
            'jumlah_gagal' => $stats['failed'],
            'jumlah_dilewati' => $stats['skipped'],
            'keterangan' => $keterangan
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'total_records' => $totalProcessed,
            'success_records' => $stats['imported'],
            'skipped_records' => $stats['skipped'],
            'failed_records' => $stats['failed'],
            'errors' => $stats['errors'],
            'riwayat_id' => $riwayat->id
        ]);
        
    } catch (\Exception $e) {
        DB::rollback();
        
        // Update riwayat jika ada error
        if (isset($riwayat)) {
            $riwayat->update([
                'status' => 'failed',
                'keterangan' => 'Import mutasi gagal: ' . $e->getMessage()
            ]);
        }
        
        Log::error('Import Mutasi error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal import data mutasi: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Import ABK data
     */
    private function importABK($file, $skipDuplicates)
    {
        try {
            DB::beginTransaction();
            
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Skip header row
            array_shift($rows);
            
            $imported = 0;
            $failed = 0;
            $errors = [];
            
            foreach ($rows as $index => $row) {
                try {
                    if (empty(array_filter($row))) continue;
                    
                    $rowNum = $index + 2;
                    
                    if (empty($row[0]) || empty($row[1])) {
                        $errors[] = "Baris {$rowNum}: NRP dan Nama ABK wajib diisi";
                        $failed++;
                        continue;
                    }
                    
                    // Check for duplicates
                    if ($skipDuplicates && ABKNew::where('id', $row[0])->exists()) {
                        continue; // Skip duplicate
                    }
                    
                    ABKNew::create([
                        'id' => $row[0],
                        'nama_abk' => $row[1],
                        'id_jabatan_tetap' => $this->getJabatanId($row[2]) ?: 1,
                        'status_abk' => $row[3] ?: 'Organik'
                    ]);
                    
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNum}: " . $e->getMessage();
                    $failed++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Import selesai. {$imported} data berhasil, {$failed} gagal",
                'total_records' => count($rows),
                'success_records' => $imported,
                'failed_records' => $failed,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Import ABK error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Helper: Get jabatan ID by name
     */
    private function getJabatanId($namaJabatan)
    {
        if (empty($namaJabatan)) return null;
        
        $jabatan = Jabatan::where('nama_jabatan', 'LIKE', '%' . trim($namaJabatan) . '%')->first();
        return $jabatan ? $jabatan->id : null;
    }

    /**
     * Helper: Get kapal ID by name
     */
    private function getKapalId($namaKapal)
    {
        if (empty($namaKapal)) return null;
        
        $kapal = Kapal::where('nama_kapal', 'LIKE', '%' . trim($namaKapal) . '%')->first();
        return $kapal ? $kapal->id : null;
    }

    /**
     * Helper: Parse date string
     */
    private function parseDate($dateString)
    {
        if (empty($dateString)) return null;
        
        try {
            return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
