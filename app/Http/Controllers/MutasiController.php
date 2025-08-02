<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\ABKNew;
use App\Models\Kapal;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MutasiController extends Controller
{
    /**
     * Display a listing of mutations
     */
    public function index()
    {
        // Ambil semua mutasi dengan relasi
        $mutasiList = Mutasi::with(['abkNaik', 'abkTurun', 'jabatanTetapNaik', 'jabatanTetapTurun', 'jabatanMutasi'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Statistik mutasi
        $statistics = [
            'total_mutasi' => Mutasi::count(),
            'mutasi_bulan_ini' => Mutasi::whereMonth('TMT', now()->month)
                ->whereYear('TMT', now()->year)
                ->count(),
            'mutasi_draft' => Mutasi::where('status_mutasi', 'Draft')->count(),
            'mutasi_disetujui' => Mutasi::where('status_mutasi', 'Disetujui')->count(),
            'mutasi_selesai' => Mutasi::where('status_mutasi', 'Selesai')->count(),
        ];

        // Mutasi terbaru
        $mutasiTerbaru = Mutasi::with(['abkNaik', 'jabatanMutasi'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('mutasi.index', [
            'mutasiList' => $mutasiList,
            'statistics' => $statistics,
            'mutasiTerbaru' => $mutasiTerbaru
        ]);
    }

    /**
     * Show the form for creating a new mutation
     */
    public function create()
    {
        // Data kapal diambil dari database table kapal
        $daftarKapal = Kapal::select('id', 'nama_kapal')
            ->orderBy('nama_kapal')
            ->get()
            ->map(function($kapal) {
                return [
                    'id_kapal' => $kapal->id,
                    'nama_kapal' => $kapal->nama_kapal,
                    'id' => $kapal->id // untuk kompatibilitas dengan view yang ada
                ];
            });

        // Data jabatan dari database
        $daftarJabatan = Jabatan::orderBy('nama_jabatan')->get();

        return view('mutasi.create', compact('daftarKapal', 'daftarJabatan'));
    }

    /**
     * Store a newly created mutation
     */
    public function store(Request $request)
    {
        $request->validate([
            // Data kapal (untuk konteks form, tidak disimpan)
            'id_kapal' => 'required|exists:kapal,id', // Validasi kapal harus ada di database
            
            // Data ABK naik
            'nrp_naik' => 'required|string|max:255',
            'nama_naik' => 'required|string|max:255',
            'jabatan_naik' => 'required|exists:jabatan,id',
            'id_jabatan_mutasi' => 'required|exists:jabatan,id',
            
            // Data mutasi
            'nama_mutasi' => 'required|string|max:255',
            'jenis_mutasi' => 'required|in:Sementara,Definitif',
            'TMT' => 'required|date',
            'TAT' => 'required|date|after:TMT',
            
            // Data ABK turun (opsional)
            'nrp_turun' => 'nullable|string|max:255',
            'nama_turun' => 'nullable|string|max:255',
            'jabatan_turun' => 'nullable|exists:jabatan,id',
            'alasan_turun' => 'nullable|string|max:255',
            'keterangan_turun' => 'nullable|string|max:500',
            
            // Upload dokumen (opsional)
            'dokumen_sertijab' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'dokumen_familisasi' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'dokumen_lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            
            // Catatan
            'catatan' => 'nullable|string',
            'perlu_sertijab' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Validasi kapal exists
            $kapal = Kapal::findOrFail($request->id_kapal);

            // Cari atau buat ABK naik
            $abkNaik = ABKNew::where('id', $request->nrp_naik)->first();
            if (!$abkNaik) {
                // Buat ABK baru jika belum ada
                $abkNaik = ABKNew::create([
                    'id' => $request->nrp_naik,
                    'nama_abk' => $request->nama_naik,
                    'id_jabatan_tetap' => $request->jabatan_naik,
                    'status_abk' => 'Organik'
                ]);
            }

            // Cari ABK turun jika ada
            $abkTurun = null;
            if ($request->filled('nrp_turun')) {
                $abkTurun = ABKNew::where('id', $request->nrp_turun)->first();
                if (!$abkTurun) {
                    // Buat ABK turun baru jika belum ada
                    $abkTurun = ABKNew::create([
                        'id' => $request->nrp_turun,
                        'nama_abk' => $request->nama_turun,
                        'id_jabatan_tetap' => $request->jabatan_turun,
                        'status_abk' => 'Organik'
                    ]);
                }
            }

            // Data mutasi yang akan disimpan
            $mutasiData = [
                'id_abk_naik' => $abkNaik->id,
                'nama_lengkap_naik' => $request->nama_naik,
                'jabatan_tetap_naik' => $request->jabatan_naik,
                'id_jabatan_mutasi' => $request->id_jabatan_mutasi,
                'nama_mutasi' => $request->nama_mutasi,
                'jenis_mutasi' => $request->jenis_mutasi,
                'TMT' => $request->TMT,
                'TAT' => $request->TAT,
                'status_mutasi' => 'Draft',
                'catatan' => $request->catatan,
                'perlu_sertijab' => $request->boolean('perlu_sertijab', true)
            ];

            // Tambahkan data ABK turun jika ada
            if ($abkTurun) {
                $mutasiData['id_abk_turun'] = $abkTurun->id;
                $mutasiData['nama_lengkap_turun'] = $request->nama_turun;
                $mutasiData['jabatan_tetap_turun'] = $request->jabatan_turun;
            }

            // Simpan data mutasi
            $mutasi = Mutasi::create($mutasiData);

            // Upload dokumen jika ada
            if ($request->hasFile('dokumen_sertijab')) {
                $mutasi->uploadDokumen($request->file('dokumen_sertijab'), 'sertijab');
            }

            if ($request->hasFile('dokumen_familisasi')) {
                $mutasi->uploadDokumen($request->file('dokumen_familisasi'), 'familisasi');
            }

            if ($request->hasFile('dokumen_lampiran')) {
                $mutasi->uploadDokumen($request->file('dokumen_lampiran'), 'lampiran');
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Data mutasi berhasil ditambahkan',
                'mutasi_id' => $mutasi->id,
                'kapal_info' => [
                    'id' => $kapal->id,
                    'nama' => $kapal->nama_kapal
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data mutasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified mutation
     */
    public function show($id)
    {
        try {
            $mutasi = Mutasi::with([
                'abkNaik', 
                'abkTurun', 
                'jabatanTetapNaik', 
                'jabatanTetapTurun', 
                'jabatanMutasi'
            ])->findOrFail($id);
            
            return view('mutasi.show', compact('mutasi'));
        } catch (\Exception $e) {
            return back()->with('error', 'Data mutasi tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified mutation
     */
    public function edit($id)
    {
        $mutasi = Mutasi::with([
            'abkNaik', 
            'abkTurun', 
            'jabatanTetapNaik', 
            'jabatanTetapTurun', 
            'jabatanMutasi'
        ])->findOrFail($id);

        $daftarKapal = [
            ['id_kapal' => 1, 'nama_kapal' => 'KM BINAIYA', 'id' => '113'],
            ['id_kapal' => 2, 'nama_kapal' => 'KM BUKIT RAYA', 'id' => '114'],
            ['id_kapal' => 3, 'nama_kapal' => 'KM CIREMAI', 'id' => '115'],
            ['id_kapal' => 4, 'nama_kapal' => 'KM DOBONSOLO', 'id' => '116'],
            ['id_kapal' => 5, 'nama_kapal' => 'KM EGON', 'id' => '117'],
            ['id_kapal' => 6, 'nama_kapal' => 'KM FUDI', 'id' => '118'],
            ['id_kapal' => 7, 'nama_kapal' => 'KM GUNUNG DEMPO', 'id' => '119'],
            ['id_kapal' => 8, 'nama_kapal' => 'KM KELUD', 'id' => '120'],
            ['id_kapal' => 9, 'nama_kapal' => 'KM LEUSER', 'id' => '121'],
            ['id_kapal' => 10, 'nama_kapal' => 'KM LABOBAR', 'id' => '122'],
        ];

        $daftarJabatan = Jabatan::orderBy('nama_jabatan')->get();
        
        return view('mutasi.edit', compact('mutasi', 'daftarKapal', 'daftarJabatan'));
    }

    /**
     * Update the specified mutation
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap_naik' => 'required|string|max:255',
            'jabatan_tetap_naik' => 'required|exists:jabatan,id',
            'id_jabatan_mutasi' => 'required|exists:jabatan,id',
            'nama_mutasi' => 'required|string|max:255',
            'jenis_mutasi' => 'required|in:Sementara,Definitif',
            'TMT' => 'required|date',
            'TAT' => 'required|date|after:TMT',
            'status_mutasi' => 'required|in:Draft,Disetujui,Ditolak,Selesai',
            'nama_lengkap_turun' => 'nullable|string|max:255',
            'jabatan_tetap_turun' => 'nullable|exists:jabatan,id',
            'catatan' => 'nullable|string',
            'perlu_sertijab' => 'boolean'
        ]);

        try {
            $mutasi = Mutasi::findOrFail($id);
            
            $updateData = [
                'nama_lengkap_naik' => $request->nama_lengkap_naik,
                'jabatan_tetap_naik' => $request->jabatan_tetap_naik,
                'id_jabatan_mutasi' => $request->id_jabatan_mutasi,
                'nama_mutasi' => $request->nama_mutasi,
                'jenis_mutasi' => $request->jenis_mutasi,
                'TMT' => $request->TMT,
                'TAT' => $request->TAT,
                'status_mutasi' => $request->status_mutasi,
                'catatan' => $request->catatan,
                'perlu_sertijab' => $request->boolean('perlu_sertijab', true)
            ];

            if ($request->filled('nama_lengkap_turun')) {
                $updateData['nama_lengkap_turun'] = $request->nama_lengkap_turun;
                $updateData['jabatan_tetap_turun'] = $request->jabatan_tetap_turun;
            }

            $mutasi->update($updateData);
            
            return response()->json([
                'success' => true,
                'message' => 'Data mutasi berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data mutasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified mutation
     */
    public function destroy($id)
    {
        try {
            $mutasi = Mutasi::findOrFail($id);
            
            // Hapus file dokumen terkait
            $mutasi->deleteAllDokumen();
            
            // Hapus data mutasi
            $mutasi->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data mutasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data mutasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ABK list for AJAX
     */
    public function getAbkList(Request $request)
    {
        try {
            $search = $request->get('search', '');
            
            $abkList = ABKNew::with('jabatanTetap')
                ->when($search, function($query, $search) {
                    return $query->where('nama_abk', 'LIKE', "%{$search}%")
                                 ->orWhere('id', 'LIKE', "%{$search}%");
                })
                ->orderBy('nama_abk')
                ->limit(20)
                ->get()
                ->map(function ($abk) {
                    return [
                        'id' => $abk->id,
                        'nrp' => $abk->id,
                        'nama_abk' => $abk->nama_abk,
                        'jabatan' => $abk->jabatanTetap ? $abk->jabatanTetap->nama_jabatan : 'Tidak ada',
                        'status' => $abk->status_abk,
                        'text' => $abk->id . ' - ' . $abk->nama_abk
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $abkList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get jabatan list for AJAX
     */
    public function getJabatanList()
    {
        try {
            $jabatanList = Jabatan::select('id', 'nama_jabatan', 'kode_jabatan')
                ->orderBy('nama_jabatan')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $jabatanList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Upload dokumen mutasi
     */
    public function uploadDokumen(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|in:sertijab,familisasi,lampiran',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        try {
            $mutasi = Mutasi::findOrFail($id);
            $path = $mutasi->uploadDokumen($request->file('file'), $request->jenis);
            
            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload',
                'path' => $path,
                'url' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete dokumen mutasi
     */
    public function deleteDokumen(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|in:sertijab,familisasi,lampiran'
        ]);

        try {
            $mutasi = Mutasi::findOrFail($id);
            $result = $mutasi->deleteDokumen($request->jenis);
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen tidak ditemukan'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve mutation
     */
    public function approve($id)
    {
        try {
            $mutasi = Mutasi::findOrFail($id);
            
            if ($mutasi->status_mutasi !== 'Draft') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya mutasi dengan status Draft yang dapat disetujui'
                ], 400);
            }
            
            $mutasi->update(['status_mutasi' => 'Disetujui']);
            
            return response()->json([
                'success' => true,
                'message' => 'Mutasi berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui mutasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject mutation
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:500'
        ]);

        try {
            $mutasi = Mutasi::findOrFail($id);
            
            if ($mutasi->status_mutasi !== 'Draft') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya mutasi dengan status Draft yang dapat ditolak'
                ], 400);
            }
            
            $mutasi->update([
                'status_mutasi' => 'Ditolak',
                'catatan' => $request->catatan
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Mutasi berhasil ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak mutasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete mutation
     */
    public function complete($id)
    {
        try {
            $mutasi = Mutasi::findOrFail($id);
            
            if ($mutasi->status_mutasi !== 'Disetujui') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya mutasi yang disetujui yang dapat diselesaikan'
                ], 400);
            }
            
            $mutasi->update(['status_mutasi' => 'Selesai']);
            
            return response()->json([
                'success' => true,
                'message' => 'Mutasi berhasil diselesaikan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan mutasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get kapal list for select2/dropdown - AJAX endpoint
     */
    public function getKapalList(Request $request)
    {
        try {
            $search = $request->get('search', '');
            
            $kapalList = Kapal::when($search, function($query, $search) {
                    return $query->where('nama_kapal', 'LIKE', "%{$search}%")
                                 ->orWhere('id', 'LIKE', "%{$search}%");
                })
                ->orderBy('nama_kapal')
                ->limit(20)
                ->get()
                ->map(function ($kapal) {
                    return [
                        'id' => $kapal->id,
                        'nama_kapal' => $kapal->nama_kapal,
                        'kode_kapal' => $kapal->id,
                        'text' => $kapal->nama_kapal . ' (' . $kapal->id . ')',
                        'tipe_pax' => $kapal->tipe_pax ?? 0,
                        'home_base' => $kapal->home_base ?? '-'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $kapalList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
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
                        $q->where('nama_abk', 'LIKE', "%{$search}%")
                          ->orWhere('id', 'LIKE', "%{$search}%");
                    });
                })
                ->where('status_abk', '!=', 'Pensiun') // Exclude pensiun
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
     * Get ABK detail by ID/NRP
     */
    public function getAbkDetail($id)
    {
        try {
            $abk = ABKNew::with('jabatanTetap')->find($id);
            
            if (!$abk) {
                return response()->json([
                    'success' => false,
                    'message' => 'ABK tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $abk->id,
                    'nrp' => $abk->id,
                    'nama_abk' => $abk->nama_abk,
                    'jabatan_id' => $abk->id_jabatan_tetap,
                    'jabatan_nama' => $abk->jabatanTetap ? $abk->jabatanTetap->nama_jabatan : 'Tidak ada',
                    'status_abk' => $abk->status_abk
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
