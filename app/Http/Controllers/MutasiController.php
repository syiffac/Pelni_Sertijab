<?php
// filepath: c:\laragon\www\SertijabPelni\app\Http\Controllers\MutasiController.php

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
        // Ambil semua mutasi dengan relasi lengkap dan pagination
        $mutasiList = Mutasi::with([
                'kapal',
                'abkNaik', 
                'abkTurun', 
                'jabatanTetapNaik', 
                'jabatanTetapTurun', 
                'jabatanMutasi',
                'jabatanMutasiTurun'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // 10 data per halaman
        
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

        return view('mutasi.index', compact('mutasiList', 'statistics', 'mutasiTerbaru', 'chartData'));
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
            'ada_abk_turun' => 'nullable|in:0,1',  // Accept both 0 and 1 as valid values
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
                'perlu_sertijab' => $request->boolean('perlu_sertijab', true)
            ];

            // Fix: Pastikan field data ABK turun disi jika adaAbkTurun
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
            \Log::info('Mutasi Data to be created:', $mutasiData);

            $mutasi = Mutasi::create($mutasiData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data mutasi berhasil ditambahkan',
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
                'ada_abk_turun' => $adaAbkTurun
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error saving mutation: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data mutasi: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
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