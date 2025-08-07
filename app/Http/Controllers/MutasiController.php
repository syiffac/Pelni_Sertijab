<?php
// filepath: c:\laragon\www\SertijabPelni\app\Http\Controllers\MutasiController.php

namespace App\Http\Controllers;

use App\Models\Kapal;
use App\Models\ABKNew;
use App\Models\Mutasi;
use App\Models\Jabatan;
use App\Models\Sertijab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MutasiController extends Controller
{
    /**
     * Display a listing of mutations
     */
    public function index(Request $request)
    {
        // PERBAIKAN: Handle filtering dengan query builder
        $query = Mutasi::with([
            'kapal',
            'abkNaik', 
            'abkTurun', 
            'jabatanTetapNaik', 
            'jabatanTetapTurun', 
            'jabatanMutasi',
            'jabatanMutasiTurun'
        ]);

        // Apply filters berdasarkan request
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap_naik', 'like', "%{$search}%")
                  ->orWhere('id_abk_naik', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap_turun', 'like', "%{$search}%")
                  ->orWhere('id_abk_turun', 'like', "%{$search}%")
                  ->orWhereHas('kapal', function($kapalQuery) use ($search) {
                      $kapalQuery->where('nama_kapal', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status_mutasi', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_mutasi', $request->jenis);
        }

        if ($request->filled('periode')) {
            $periode = $request->periode; // Format: YYYY-MM
            $query->whereYear('TMT', substr($periode, 0, 4))
                  ->whereMonth('TMT', substr($periode, 5, 2));
        }

        // Order by dan pagination
        $mutasiList = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistik mutasi dengan data aktual
        $statistics = [
            'total_mutasi' => Mutasi::count(),
            'mutasi_selesai' => Mutasi::where('status_mutasi', 'Selesai')->count(),
            'mutasi_proses' => Mutasi::whereIn('status_mutasi', ['Draft', 'Disetujui'])->count(),
            'mutasi_bulan_ini' => Mutasi::whereMonth('TMT', now()->month)
                ->whereYear('TMT', now()->year)
                ->count(),
        ];

        // Mutasi terbaru untuk sidebar atau info
        $mutasiTerbaru = Mutasi::with(['kapal', 'abkNaik', 'jabatanMutasi'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Data untuk chart/grafik (opsional)
        $chartData = [
            'mutasi_per_bulan' => $this->getMutasiPerBulan(),
            'mutasi_per_kapal' => $this->getMutasiPerKapal(),
            'mutasi_per_status' => $this->getMutasiPerStatus(),
        ];

        // Tambahkan data kapal untuk filter
        $kapals = \App\Models\Kapal::orderBy('nama_kapal')->get();
        
        return view('mutasi.index', compact('mutasiList', 'statistics', 'mutasiTerbaru', 'chartData', 'kapals'));
    }

    /**
     * Show the form for creating a new mutation
     */
    public function create()
    {
        $daftarKapal = Kapal::select('id', 'nama_kapal', 'tipe_pax', 'home_base')
            ->orderBy('nama_kapal')
            ->get()
            ->map(function($kapal) {
                return [
                    'id_kapal' => $kapal->id,
                    'nama_kapal' => $kapal->nama_kapal,
                    'id' => $kapal->id,
                    'tipe_pax' => $kapal->tipe_pax ?? 0,
                    'home_base' => $kapal->home_base ?? '-'
                ];
            });

        $daftarJabatan = Jabatan::select('id', 'nama_jabatan')
            ->orderBy('nama_jabatan')
            ->get();

        return view('mutasi.create', compact('daftarKapal', 'daftarJabatan'));
    }

    /**
     * Search ABK for Select2 - untuk ABK Naik dan Turun
     */
    public function searchAbk(Request $request)
    {
        try {
            $search = $request->get('q', '');
            $type = $request->get('type', 'naik'); // 'naik' atau 'turun'
            
            $abkList = ABKNew::with('jabatanTetap')
                ->when($search, function($query, $search) {
                    return $query->where(function($q) use ($search) {
                        $q->where('id', 'LIKE', "%{$search}%")
                          ->orWhere('nama_abk', 'LIKE', "%{$search}%");
                    });
                })
                ->where('status_abk', '!=', 'Pensiun')
                ->orderBy('nama_abk')
                ->limit(20)
                ->get()
                ->map(function ($abk) {
                    return [
                        'id' => $abk->id,
                        'text' => $abk->id . ' - ' . $abk->nama_abk,
                        'nrp' => $abk->id,
                        'nama_abk' => $abk->nama_abk,
                        'jabatan_id' => $abk->id_jabatan_tetap,
                        'jabatan_nama' => $abk->jabatanTetap ? $abk->jabatanTetap->nama_jabatan : 'Tidak ada',
                        'status_abk' => $abk->status_abk
                    ];
                });

            return response()->json([
                'results' => $abkList,
                'pagination' => ['more' => false]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'results' => [],
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created mutation
     */
    public function store(Request $request)
{
    $request->validate([
        'id_kapal' => 'required|integer|exists:kapal,id',
        'nrp_naik' => 'required|string|max:255',
        'nama_naik' => 'required|string|max:255',
        'jabatan_naik' => 'required|exists:jabatan,id',
        'id_jabatan_mutasi' => 'required|exists:jabatan,id',
        'nama_mutasi' => 'required|string|max:255',
        'jenis_mutasi' => 'required|in:Sementara,Definitif',
        'TMT' => 'required|date',
        'TAT' => 'required|date|after:TMT',
        'ada_abk_turun' => 'nullable|in:0,1',
        'nrp_turun' => 'nullable|string|max:255|required_if:ada_abk_turun,1',
        'nama_turun' => 'nullable|string|max:255|required_if:ada_abk_turun,1',
        'jabatan_turun' => 'nullable|exists:jabatan,id|required_if:ada_abk_turun,1',
        'id_jabatan_mutasi_turun' => 'nullable|exists:jabatan,id|required_if:ada_abk_turun,1',
        'nama_mutasi_turun' => 'nullable|string|max:255|required_if:ada_abk_turun,1',
        'jenis_mutasi_turun' => 'nullable|in:Sementara,Definitif|required_if:ada_abk_turun,1',
        'TMT_turun' => 'nullable|date|required_if:ada_abk_turun,1',
        'TAT_turun' => 'nullable|date|after:TMT_turun|required_if:ada_abk_turun,1',
        'keterangan_turun' => 'nullable|string|max:1000',
        'catatan' => 'nullable|string',
        'perlu_sertijab' => 'boolean'
    ]);

    try {
        DB::beginTransaction();

        $kapal = Kapal::findOrFail($request->id_kapal);

        $abkNaik = ABKNew::where('id', $request->nrp_naik)->first();
        if (!$abkNaik) {
            return response()->json([
                'success' => false,
                'message' => 'ABK naik dengan NRP ' . $request->nrp_naik . ' tidak ditemukan'
            ], 404);
        }

        // Fix: Gunakan filter_var untuk lebih aman
        $adaAbkTurun = filter_var($request->input('ada_abk_turun'), FILTER_VALIDATE_BOOLEAN);
        $abkTurun = null;
        
        if ($adaAbkTurun && $request->filled('nrp_turun')) {
            $abkTurun = ABKNew::where('id', $request->nrp_turun)->first();
            if (!$abkTurun) {
                return response()->json([
                    'success' => false,
                    'message' => 'ABK turun dengan NRP ' . $request->nrp_turun . ' tidak ditemukan'
                ], 404);
            }
        }

        $perluSertijab = $request->boolean('perlu_sertijab', true);

        $mutasiData = [
            'id_kapal' => (int) $kapal->id,
            'nama_kapal' => $kapal->nama_kapal,
            'id_abk_naik' => $abkNaik->id,
            'nama_lengkap_naik' => $abkNaik->nama_abk,
            'jabatan_tetap_naik' => (int) $abkNaik->id_jabatan_tetap,
            'id_jabatan_mutasi' => (int) $request->id_jabatan_mutasi,
            'nama_mutasi' => $request->nama_mutasi,
            'jenis_mutasi' => $request->jenis_mutasi,
            'TMT' => $request->TMT,
            'TAT' => $request->TAT,
            'ada_abk_turun' => $adaAbkTurun,
            'status_mutasi' => 'Draft',
            'catatan' => $request->catatan,
            'perlu_sertijab' => $perluSertijab
        ];

        // Fix: Pastikan field data ABK turun diisi jika adaAbkTurun
        if ($adaAbkTurun) {
            if ($abkTurun) {
                $mutasiData['id_abk_turun'] = $abkTurun->id;
                $mutasiData['nama_lengkap_turun'] = $abkTurun->nama_abk;
                $mutasiData['jabatan_tetap_turun'] = (int) $abkTurun->id_jabatan_tetap;
            }
            
            // Fix: Pastikan data mutasi turun diisi dari request
            $mutasiData['id_jabatan_mutasi_turun'] = (int) $request->id_jabatan_mutasi_turun;
            $mutasiData['nama_mutasi_turun'] = $request->nama_mutasi_turun;
            $mutasiData['jenis_mutasi_turun'] = $request->jenis_mutasi_turun;
            $mutasiData['TMT_turun'] = $request->TMT_turun;
            $mutasiData['TAT_turun'] = $request->TAT_turun;
            $mutasiData['keterangan_turun'] = $request->keterangan_turun;
        }

        // Debug sebelum create
        Log::info('Mutasi Data to be created:', $mutasiData);

        $mutasi = Mutasi::create($mutasiData);

        // PERBAIKAN: Otomatis membuat record sertijab jika perlu_sertijab bernilai true
        if ($perluSertijab) {
            Sertijab::create([
                'id_mutasi' => $mutasi->id,
                'status_dokumen' => 'draft',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            Log::info('Sertijab record otomatis dibuat untuk mutasi ID: ' . $mutasi->id);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Data mutasi berhasil ditambahkan' . ($perluSertijab ? ' dan record sertijab otomatis dibuat' : ''),
            'mutasi_id' => $mutasi->id,
            'kapal_info' => [
                'id' => $kapal->id,
                'nama' => $kapal->nama_kapal
            ],
            'abk_info' => [
                'naik' => [
                    'id' => $abkNaik->id,
                    'nama' => $abkNaik->nama_abk
                ],
                'turun' => $abkTurun ? [
                    'id' => $abkTurun->id,
                    'nama' => $abkTurun->nama_abk
                ] : null
            ],
            'ada_abk_turun' => $adaAbkTurun,
            'perlu_sertijab' => $perluSertijab
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error saving mutation: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal menambahkan data mutasi: ' . $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}

/**
 * Remove the specified mutation from storage
 */
public function destroy($id)
{
    try {
        DB::beginTransaction();

        $mutasi = Mutasi::findOrFail($id);
        
        Log::info("Attempting to delete mutasi ID: {$id}");

        // Cek apakah mutasi bisa dihapus (hanya draft yang bisa dihapus)
        if ($mutasi->status_mutasi !== 'Draft') {
            Log::warning("Cannot delete mutasi ID {$id} - Status: {$mutasi->status_mutasi}");
            return response()->json([
                'success' => false,
                'message' => 'Hanya mutasi dengan status Draft yang dapat dihapus'
            ], 400);
        }

        // PERBAIKAN: Hapus data sertijab yang terkait terlebih dahulu
        $sertijab = Sertijab::where('id_mutasi', $mutasi->id)->first();
        if ($sertijab) {
            // Hapus file dokumen yang terkait
            $filesToDelete = [
                $sertijab->dokumen_sertijab_path,
                $sertijab->dokumen_familisasi_path,
                $sertijab->dokumen_lampiran_path
            ];
            
            foreach ($filesToDelete as $filePath) {
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                    Log::info("Deleted file: {$filePath}");
                }
            }
            
            // Hapus record sertijab
            $sertijab->delete();
            Log::info("Deleted sertijab record for mutasi ID: {$id}");
        }

        // Hapus file terkait mutasi jika ada
        if ($mutasi->file_surat_mutasi && Storage::disk('public')->exists($mutasi->file_surat_mutasi)) {
            Storage::disk('public')->delete($mutasi->file_surat_mutasi);
            Log::info("Deleted file: {$mutasi->file_surat_mutasi}");
        }

        if ($mutasi->file_lampiran && Storage::disk('public')->exists($mutasi->file_lampiran)) {
            Storage::disk('public')->delete($mutasi->file_lampiran);
            Log::info("Deleted file: {$mutasi->file_lampiran}");
        }

        // Hapus mutasi
        $mutasi->delete();
        Log::info("Successfully deleted mutasi ID: {$id}");

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Mutasi berhasil dihapus'
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        DB::rollback();
        Log::error("Mutasi not found: {$id}");
        
        return response()->json([
            'success' => false,
            'message' => 'Data mutasi tidak ditemukan'
        ], 404);
        
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error deleting mutation: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus mutasi: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Show the specified mutation (for modal detail)
     */
    public function show($id)
{
    try {
        $mutasi = Mutasi::with([
            'kapal',
            'abkNaik',
            'abkTurun',
            'jabatanTetapNaik',
            'jabatanTetapTurun',
            'jabatanMutasi',
            'jabatanMutasiTurun',
            'sertijab'
        ])->findOrFail($id);

        // Get sertijab data if exists
        $sertijab = null;
        if ($mutasi->perlu_sertijab) {
            $sertijab = Sertijab::where('id_mutasi', $mutasi->id)->first();
        }

        // If request is AJAX (for progress checking), return JSON
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'progress' => $sertijab ? $sertijab->verification_progress : 0,
                'status' => $sertijab ? $sertijab->status_dokumen : 'no_sertijab',
                'last_updated' => $mutasi->updated_at->toISOString()
            ]);
        }

        return view('mutasi.show', compact('mutasi', 'sertijab'));

    } catch (\Exception $e) {
        Log::error('Error showing mutasi detail: ' . $e->getMessage());
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail mutasi'
            ], 500);
        }

        return redirect()->route('mutasi.index')
            ->with('error', 'Gagal memuat detail mutasi: ' . $e->getMessage());
    }
}

    /**
     * Export mutasi data
     */
    public function export(Request $request)
    {
        try {
            // Build query dengan filter yang sama seperti index
            $query = Mutasi::with([
                'kapal',
                'abkNaik',
                'abkTurun',
                'jabatanTetapNaik',
                'jabatanTetapTurun',
                'jabatanMutasi',
                'jabatanMutasiTurun'
            ]);

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap_naik', 'like', "%{$search}%")
                      ->orWhere('nama_lengkap_turun', 'like', "%{$search}%")
                      ->orWhere('id_abk_naik', 'like', "%{$search}%")
                      ->orWhere('id_abk_turun', 'like', "%{$search}%")
                      ->orWhereHas('kapal', function($kapal) use ($search) {
                          $kapal->where('nama_kapal', 'like', "%{$search}%");
                      });
                });
            }

            if ($request->filled('status')) {
                $query->where('status_mutasi', $request->status);
            }

            if ($request->filled('jenis')) {
                $query->where('jenis_mutasi', $request->jenis);
            }

            if ($request->filled('periode')) {
                $periode = $request->periode;
                $query->whereYear('TMT', substr($periode, 0, 4))
                      ->whereMonth('TMT', substr($periode, 5, 2));
            }

            $mutasiList = $query->orderBy('created_at', 'desc')->get();

            // Return Excel download atau PDF
            return view('mutasi.export', compact('mutasiList'));

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified mutation
     */
    public function edit($id)
    {
        try {
            $mutasi = Mutasi::with([
                'kapal',
                'abkNaik',
                'abkTurun',
                'jabatanTetapNaik',
                'jabatanTetapTurun',
                'jabatanMutasi',
                'jabatanMutasiTurun'
            ])->findOrFail($id);

            // Get data untuk dropdown
            $daftarKapal = Kapal::select('id', 'nama_kapal', 'tipe_pax', 'home_base')
                ->orderBy('nama_kapal')
                ->get()
                ->map(function($kapal) {
                    return [
                        'id' => $kapal->id,
                        'nama_kapal' => $kapal->nama_kapal,
                        'tipe_pax' => $kapal->tipe_pax ?? 0,
                        'home_base' => $kapal->home_base ?? '-'
                    ];
                });

            $daftarJabatan = Jabatan::select('id', 'nama_jabatan')
                ->orderBy('nama_jabatan')
                ->get();

            return view('mutasi.edit', compact('mutasi', 'daftarKapal', 'daftarJabatan'));

        } catch (\Exception $e) {
            Log::error('Error showing edit form: ' . $e->getMessage());
            return redirect()->route('mutasi.index')
                ->with('error', 'Gagal memuat form edit: ' . $e->getMessage());
        }
    }

/**
 * Update the specified mutation in storage
 */
public function update(Request $request, $id)
{
    // PERBAIKAN: Validasi yang disesuaikan dengan field database
    $request->validate([
        'id_kapal' => 'required|exists:kapal,id',
        'nrp_naik' => 'required|string|max:255', // Ubah dari exists:abk,id ke string
        'nama_naik' => 'required|string|max:255',
        'jabatan_naik' => 'required|exists:jabatan,id',
        'id_jabatan_mutasi' => 'required|exists:jabatan,id',
        'nama_mutasi' => 'required|string|max:255',
        'jenis_mutasi' => 'required|in:Sementara,Definitif',
        'TMT' => 'required|date',
        'TAT' => 'required|date|after:TMT',
        'status_mutasi' => 'required|in:Draft,Disetujui,Ditolak,Selesai',
        'perlu_sertijab' => 'nullable|boolean',
        'catatan' => 'nullable|string',
        
        // ABK Turun (opsional)
        'ada_abk_turun' => 'nullable|boolean',
        'nrp_turun' => 'nullable|string|max:255', // Ubah dari exists:abk,id ke string
        'nama_turun' => 'nullable|string|max:255',
        'jabatan_turun' => 'nullable|exists:jabatan,id',
        'id_jabatan_mutasi_turun' => 'nullable|exists:jabatan,id',
        'nama_mutasi_turun' => 'nullable|string|max:255',
        'jenis_mutasi_turun' => 'nullable|in:Sementara,Definitif',
        'TMT_turun' => 'nullable|date',
        'TAT_turun' => 'nullable|date|after:TMT_turun',
        'keterangan_turun' => 'nullable|string',
    ]);

    try {
        DB::beginTransaction();

        $mutasi = Mutasi::findOrFail($id);
        
        Log::info('Updating mutasi ID: ' . $id);
        Log::info('Request data: ', $request->all());

        // PERBAIKAN: Ambil data kapal untuk update nama_kapal
        $kapal = Kapal::findOrFail($request->id_kapal);

        // PERBAIKAN: Mapping field yang benar sesuai dengan struktur database
        $updateData = [
            'id_kapal' => $request->id_kapal,
            'nama_kapal' => $kapal->nama_kapal, // Update nama kapal
            'id_abk_naik' => $request->nrp_naik,
            'nama_lengkap_naik' => $request->nama_naik,
            'jabatan_tetap_naik' => $request->jabatan_naik,
            'id_jabatan_mutasi' => $request->id_jabatan_mutasi,
            'nama_mutasi' => $request->nama_mutasi,
            'jenis_mutasi' => $request->jenis_mutasi,
            'TMT' => $request->TMT,
            'TAT' => $request->TAT,
            'status_mutasi' => $request->status_mutasi,
            'perlu_sertijab' => $request->boolean('perlu_sertijab', false),
            'catatan' => $request->catatan,
        ];

        // PERBAIKAN: Handle ABK Turun dengan benar
        $adaAbkTurun = $request->boolean('ada_abk_turun', false);
        
        if ($adaAbkTurun) {
            $updateData['ada_abk_turun'] = true;
            $updateData['id_abk_turun'] = $request->nrp_turun;
            $updateData['nama_lengkap_turun'] = $request->nama_turun;
            $updateData['jabatan_tetap_turun'] = $request->jabatan_turun;
            $updateData['id_jabatan_mutasi_turun'] = $request->id_jabatan_mutasi_turun;
            $updateData['nama_mutasi_turun'] = $request->nama_mutasi_turun;
            $updateData['jenis_mutasi_turun'] = $request->jenis_mutasi_turun;
            $updateData['TMT_turun'] = $request->TMT_turun;
            $updateData['TAT_turun'] = $request->TAT_turun;
            $updateData['keterangan_turun'] = $request->keterangan_turun;
        } else {
            // Reset semua field ABK turun jika tidak ada
            $updateData['ada_abk_turun'] = false;
            $updateData['id_abk_turun'] = null;
            $updateData['nama_lengkap_turun'] = null;
            $updateData['jabatan_tetap_turun'] = null;
            $updateData['id_jabatan_mutasi_turun'] = null;
            $updateData['nama_mutasi_turun'] = null;
            $updateData['jenis_mutasi_turun'] = null;
            $updateData['TMT_turun'] = null;
            $updateData['TAT_turun'] = null;
            $updateData['keterangan_turun'] = null;
        }

        Log::info('Update data prepared: ', $updateData);

        // PERBAIKAN: Update dengan data yang sudah disiapkan
        $updated = $mutasi->update($updateData);
        
        Log::info('Update result: ' . ($updated ? 'success' : 'failed'));
        
        if (!$updated) {
            throw new \Exception('Gagal mengupdate data ke database');
        }

        // PERBAIKAN: Handle perubahan perlu_sertijab
        $perluSertijab = $request->boolean('perlu_sertijab', false);
        $sertijab = Sertijab::where('id_mutasi', $mutasi->id)->first();
        
        if ($perluSertijab && !$sertijab) {
            // Buat record sertijab baru jika diperlukan tapi belum ada
            Sertijab::create([
                'id_mutasi' => $mutasi->id,
                'status_dokumen' => 'draft',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            Log::info('Created new sertijab record for mutasi ID: ' . $mutasi->id);
        } elseif (!$perluSertijab && $sertijab) {
            // Hapus record sertijab jika tidak diperlukan lagi
            $sertijab->delete();
            Log::info('Deleted sertijab record for mutasi ID: ' . $mutasi->id);
        }

        DB::commit();
        
        Log::info('Successfully updated mutasi ID: ' . $id);

        // Response untuk AJAX
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data mutasi berhasil diupdate!',
                'redirect_url' => route('mutasi.index'),
                'mutasi_id' => $mutasi->id
            ]);
        }

        return redirect()->route('mutasi.index')
            ->with('success', 'Data mutasi berhasil diupdate!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollback();
        Log::error('Validation error updating mutasi: ', $e->errors());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }

        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error updating mutasi: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update mutasi: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal update mutasi: ' . $e->getMessage());
    }
}

    // Helper methods for charts
    private function getMutasiPerBulan()
    {
        return Mutasi::select(
                DB::raw('MONTH(TMT) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('TMT', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
    }

    private function getMutasiPerKapal()
    {
        return Mutasi::with('kapal')
            ->select('id_kapal', DB::raw('COUNT(*) as total'))
            ->groupBy('id_kapal')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'kapal' => $item->kapal ? $item->kapal->nama_kapal : 'Unknown',
                    'total' => $item->total
                ];
            });
    }

    private function getMutasiPerStatus()
    {
        return Mutasi::select('status_mutasi', DB::raw('COUNT(*) as total'))
            ->groupBy('status_mutasi')
            ->get();
    }
}