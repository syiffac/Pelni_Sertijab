<?php

namespace App\Http\Controllers;

use App\Models\ABK;
use App\Models\Kapal;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ABKExport;
use App\Exports\ABKTemplateExport;
use App\Models\Mutasi;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ABKController extends Controller
{

    /**
     * Display a listing of ABK
     */
    public function index()
    {
            // Ambil semua ABK dengan relasi kapal dan jabatan
            $abkList = ABK::with(['kapal', 'jabatanNaik', 'jabatanTurun'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Statistik ABK per kapal
            $abkPerKapal = Kapal::withCount(['abk', 'abkAktif', 'abkTidakAktif'])
                ->orderBy('nama_kapal')
                ->get();

            // Total statistik keseluruhan
            $statistics = [
                'total_abk' => ABK::count(),
                'total_kapal' => Kapal::count(),
                'abk_aktif' => ABK::where('status_abk', 'Aktif')->count(),
                'abk_tidak_aktif' => ABK::where('status_abk', '!=', 'Aktif')->count(),
                'total_jabatan' => Jabatan::count(),
            ];

            // Daftar kapal untuk filter
            $daftarKapal = Kapal::orderBy('nama_kapal')->get();

            $mutasiTerbaru = Mutasi::with(['kapalAsal', 'kapalTujuan'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return view('kelolaABK.index', [
                'abkList' => $abkList,
                'abkPerKapal' => $abkPerKapal,
                'totalStatistik' => $statistics, // ubah dari 'statistics' ke 'totalStatistik'
                'mutasiTerbaru' => $mutasiTerbaru,
                'daftarKapal' => $daftarKapal
            ]);
    }
    /**
     * Show the form for creating a new ABK
     */
    public function create()
    {
            $daftarKapal = Kapal::orderBy('nama_kapal')->get(); 
            
            // Ambil data jabatan
            $daftarJabatan = Jabatan::
                orderBy('nama_jabatan')
                ->get();
            
            // Cek apakah ada data kapal
            if ($daftarKapal->isEmpty()) {
                return redirect()->route('kapal.create')
                    ->with('warning', 'Belum ada data kapal. Silakan tambah data kapal terlebih dahulu.');
            }
            
            // Cek apakah ada data jabatan
            if ($daftarJabatan->isEmpty()) {
                return back()->with('warning', 'Belum ada data jabatan. Silakan tambah data jabatan terlebih dahulu.');
            }
            
            return view('kelolaABK.create', compact('daftarKapal', 'daftarJabatan'));
    }

    /**
     * Store a newly created ABK
     */
    public function store(Request $request)
    {
        $request->validate([
            // Data kapal
            'id_kapal' => 'required|exists:kapal,id_kapal',
            
            // Data ABK naik
            'nrp_naik' => 'required|string|max:255|unique:abk,nrp_naik',
            'nama_naik' => 'required|string|max:255',
            'jabatan_naik' => 'required|exists:jabatan,id_jabatan',
            
            // Data mutasi
            'nama_mutasi' => 'required|string|max:255',
            'jenis_mutasi' => 'required|string|max:100',
            'tmt' => 'required|date',
            'tat' => 'required|date|after:tmt',
            
            // Data ABK turun (opsional)
            'nrp_turun' => 'nullable|string|max:255',
            'nama_turun' => 'nullable|string|max:255',
            'jabatan_turun' => 'nullable|exists:jabatan,id_jabatan',
            'alasan_turun' => 'nullable|string|max:255',
            'keterangan_turun' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Data ABK yang akan disimpan
            $abkData = [
                'id_kapal' => $request->id_kapal,
                'nrp_naik' => $request->nrp_naik,
                'nama_naik' => $request->nama_naik,
                'jabatan_naik' => $request->jabatan_naik,
                'nama_mutasi' => $request->nama_mutasi,
                'jenis_mutasi' => $request->jenis_mutasi,
                'tmt' => $request->tmt,
                'tat' => $request->tat,
                'status_abk' => 'Aktif'
            ];

            // Tambahkan data ABK turun jika ada
            if ($request->filled('nrp_turun')) {
                $abkData['nrp_turun'] = $request->nrp_turun;
                $abkData['nama_turun'] = $request->nama_turun;
                $abkData['jabatan_turun'] = $request->jabatan_turun;
                $abkData['alasan_turun'] = $request->alasan_turun;
                $abkData['keterangan_turun'] = $request->keterangan_turun;
            }

            // Simpan data ABK
            ABK::create($abkData);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Data ABK berhasil ditambahkan'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data ABK: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show ABK details for specific kapal
     */
    public function showByKapal($id_kapal)
    {
        try {
            $kapal = Kapal::findOrFail($id_kapal);
            
            $abkList = ABK::with(['jabatanNaik', 'jabatanTurun'])
                ->where('id_kapal', $id_kapal)
                ->orderBy('nama_naik')
                ->paginate(20);

            $statistikKapal = [
                'total_abk' => ABK::where('id_kapal', $id_kapal)->count(),
                'abk_aktif' => ABK::where('id_kapal', $id_kapal)->where('status_abk', 'Aktif')->count(),
                'abk_tidak_aktif' => ABK::where('id_kapal', $id_kapal)->where('status_abk', '!=', 'Aktif')->count(),
            ];

            return view('kelolaABK.detailKapal', compact('kapal', 'abkList', 'statistikKapal'));
        } catch (\Exception $e) {
            return back()->with('error', 'Kapal tidak ditemukan.');
        }
    }

    /**
     * Get ABK list by kapal (AJAX)
     */
    public function getAbkByKapal(Request $request)
    {
        try {
            $id_kapal = $request->id_kapal;
            
            $abkList = ABK::with(['jabatanNaik'])
                ->where('id_kapal', $id_kapal)
                ->where('status_abk', 'Aktif')
                ->orderBy('nama_naik')
                ->get()
                ->map(function ($abk) {
                    return [
                        'id_abk' => $abk->id_abk,
                        'nrp_naik' => $abk->nrp_naik,
                        'nama_naik' => $abk->nama_naik,
                        'jabatan_naik' => $abk->jabatanNaik ? $abk->jabatanNaik->nama_jabatan : 'Tidak ada',
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
     * Show the form for editing the specified ABK
     */
    public function edit($id)
    {
            $abk = ABK::with(['kapal', 'jabatanNaik', 'jabatanTurun'])->findOrFail($id);

            $daftarKapal = Kapal::orderBy('nama_kapal')->get();

            $daftarJabatan = Jabatan::where('status_jabatan', 'Aktif')
                ->orderBy('nama_jabatan')
                ->get();
            
            return view('kelolaABK.edit', compact('abk', 'daftarKapal', 'daftarJabatan'));
    }

    /**
     * Update the specified ABK
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kapal' => 'required|exists:kapal,id_kapal',
            'nrp_naik' => 'required|string|max:255|unique:abk,nrp_naik,' . $id . ',id_abk',
            'nama_naik' => 'required|string|max:255',
            'jabatan_naik' => 'required|exists:jabatan,id_jabatan',
            'nama_mutasi' => 'required|string|max:255',
            'jenis_mutasi' => 'required|string|max:100',
            'tmt' => 'required|date',
            'tat' => 'required|date|after:tmt',
            'status_abk' => 'required|string',
            'nrp_turun' => 'nullable|string|max:255',
            'nama_turun' => 'nullable|string|max:255',
            'jabatan_turun' => 'nullable|exists:jabatan,id_jabatan',
            'alasan_turun' => 'nullable|string|max:255',
            'keterangan_turun' => 'nullable|string|max:500'
        ]);

        try {
            $abk = ABK::findOrFail($id);
            $abk->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Data ABK berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data ABK: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified ABK
     */
    public function destroy($id)
    {
        try {
            $abk = ABK::findOrFail($id);
            $abk->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data ABK berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data ABK: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show export page
     */
    public function export()
    {
            $daftarKapal = Kapal::orderBy('nama_kapal')->get();
            
            $exportStats = [
                'total_abk' => ABK::count(),
                'abk_aktif' => ABK::where('status_abk', 'Aktif')->count(),
                'total_kapal' => Kapal::count(),
            ];

            return view('kelolaABK.export', compact('daftarKapal', 'exportStats'));
    }

    /**
     * Export ABK data to Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $query = ABK::with(['kapal', 'jabatanNaik', 'jabatanTurun']);
            
            // Apply filters
            if ($request->filled('export_kapal') && $request->export_kapal != '') {
                $query->where('id_kapal', $request->export_kapal);
            }
            
            if ($request->filled('export_status') && $request->export_status != '') {
                $query->where('status_abk', $request->export_status);
            }
            
            if ($request->filled('export_start_date') && $request->filled('export_end_date')) {
                $query->whereBetween('created_at', [
                    $request->export_start_date . ' 00:00:00',
                    $request->export_end_date . ' 23:59:59'
                ]);
            }
            
            $abkData = $query->orderBy('created_at', 'desc')->get();
            
            if ($abkData->isEmpty()) {
                return back()->with('warning', 'Tidak ada data ABK yang sesuai dengan filter.');
            }
            
            return Excel::download(new ABKExport($abkData), 'data_abk_' . date('Y-m-d_H-i-s') . '.xlsx');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    /**
     * Export ABK data to PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            $query = ABK::with(['kapal', 'jabatanNaik', 'jabatanTurun']);
            
            // Apply same filters as Excel
            if ($request->filled('export_kapal') && $request->export_kapal != '') {
                $query->where('id_kapal', $request->export_kapal);
            }
            
            if ($request->filled('export_status') && $request->export_status != '') {
                $query->where('status_abk', $request->export_status);
            }
            
            if ($request->filled('export_start_date') && $request->filled('export_end_date')) {
                $query->whereBetween('created_at', [
                    $request->export_start_date . ' 00:00:00',
                    $request->export_end_date . ' 23:59:59'
                ]);
            }
            
            $abkData = $query->orderBy('created_at', 'desc')->get();
            
            if ($abkData->isEmpty()) {
                return back()->with('warning', 'Tidak ada data ABK yang sesuai dengan filter.');
            }
            
            $pdf = PDF::loadView('exports.abk_pdf', compact('abkData'))
                ->setPaper('a4', 'landscape')
                ->setOptions(['defaultFont' => 'sans-serif']);
                
            return $pdf->download('data_abk_' . date('Y-m-d_H-i-s') . '.pdf');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }

    /**
     * Get kapal list for AJAX
     */
    public function getKapalList()
    {
            $kapalList = Kapal::select('id_kapal', 'nama_kapal', 'kode_kapal')
                ->orderBy('nama_kapal')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $kapalList
            ]);
    }

    /**
     * Get jabatan list for AJAX
     */
    public function getJabatanList()
    {
        try {
            $jabatanList = Jabatan::select('id_jabatan', 'nama_jabatan', 'kode_jabatan')
                ->where('status_jabatan', 'Aktif')
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
     * Check if NRP already exists (AJAX)
     */
    public function checkNrp(Request $request)
    {
        try {
            $nrp = $request->nrp;
            $excludeId = $request->exclude_id;

            $query = ABK::where('nrp_naik', $nrp);
            
            if ($excludeId) {
                $query->where('id_abk', '!=', $excludeId);
            }

            $exists = $query->exists();

            return response()->json([
                'exists' => $exists,
                'message' => $exists ? 'NRP sudah digunakan' : 'NRP tersedia'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'exists' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the specified ABK
     */
    public function show($id)
    {
        try {
            $abk = ABK::with(['kapal', 'jabatanNaik', 'jabatanTurun'])->findOrFail($id);
            
            return view('kelolaABK.show', compact('abk'));
        } catch (\Exception $e) {
            return back()->with('error', 'Data ABK tidak ditemukan');
        }
    }

    // ====================== MUTASI METHODS ======================

    /**
     * Display a listing of mutations
     */
    public function mutasiIndex()
    {
            $mutasiList = ABK::with(['kapal', 'jabatanNaik', 'jabatanTurun'])
                ->whereNotNull('nama_mutasi')
                ->orderBy('tmt', 'desc')
                ->paginate(20);
            
            $statistics = [
                'total_mutasi' => ABK::whereNotNull('nama_mutasi')->count(),
                'mutasi_bulan_ini' => ABK::whereNotNull('nama_mutasi')
                    ->whereMonth('tmt', now()->month)
                    ->whereYear('tmt', now()->year)
                    ->count(),
                'mutasi_pending' => ABK::whereNotNull('nama_mutasi')
                    ->where('status_abk', 'Pending')
                    ->count(),
            ];

            return view('kelolaABK.mutasi.index', compact('mutasiList', 'statistics'));
    }

    /**
     * Show the form for creating a new mutation
     */
    public function mutasiCreate()
    {
            $daftarKapal = Kapal::orderBy('nama_kapal')->get();

            $daftarJabatan = Jabatan::orderBy('nama_jabatan')->get();

            return view('kelolaABK.mutasi.create', compact('daftarKapal', 'daftarJabatan'));
    }

    /**
     * Store a newly created mutation
     */
    public function mutasiStore(Request $request)
    {
        $request->validate([
            'id_kapal' => 'required|exists:kapal,id_kapal',
            'nrp_naik' => 'required|string|max:255',
            'nama_naik' => 'required|string|max:255',
            'jabatan_naik' => 'required|exists:jabatan,id_jabatan',
            'nama_mutasi' => 'required|string|max:255',
            'jenis_mutasi' => 'required|string|max:100',
            'tmt' => 'required|date',
            'tat' => 'required|date|after:tmt',
            'nrp_turun' => 'nullable|string|max:255',
            'nama_turun' => 'nullable|string|max:255',
            'jabatan_turun' => 'nullable|exists:jabatan,id_jabatan',
            'alasan_turun' => 'nullable|string|max:255',
            'keterangan_turun' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $mutasiData = [
                'id_kapal' => $request->id_kapal,
                'nrp_naik' => $request->nrp_naik,
                'nama_naik' => $request->nama_naik,
                'jabatan_naik' => $request->jabatan_naik,
                'nama_mutasi' => $request->nama_mutasi,
                'jenis_mutasi' => $request->jenis_mutasi,
                'tmt' => $request->tmt,
                'tat' => $request->tat,
                'status_abk' => 'Aktif'
            ];

            if ($request->filled('nrp_turun')) {
                $mutasiData['nrp_turun'] = $request->nrp_turun;
                $mutasiData['nama_turun'] = $request->nama_turun;
                $mutasiData['jabatan_turun'] = $request->jabatan_turun;
                $mutasiData['alasan_turun'] = $request->alasan_turun;
                $mutasiData['keterangan_turun'] = $request->keterangan_turun;
            }

            ABK::create($mutasiData);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Data mutasi berhasil ditambahkan'
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
    public function mutasiShow($id)
    {
        try {
            $mutasi = ABK::with(['kapal', 'jabatanNaik', 'jabatanTurun'])->findOrFail($id);
            
            return view('kelolaABK.mutasi.show', compact('mutasi'));
        } catch (\Exception $e) {
            return back()->with('error', 'Data mutasi tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified mutation
     */
    public function mutasiEdit($id)
    {
            $mutasi = ABK::with(['kapal', 'jabatanNaik', 'jabatanTurun'])->findOrFail($id);
            
            $daftarKapal = Kapal::orderBy('nama_kapal')->get();
            $daftarJabatan = Jabatan::orderBy('nama_jabatan')->get();
            
            return view('kelolaABK.mutasi.edit', compact('mutasi', 'daftarKapal', 'daftarJabatan'));
        }

    /**
     * Update the specified mutation
     */
    public function mutasiUpdate(Request $request, $id)
    {
        $request->validate([
            'id_kapal' => 'required|exists:kapal,id_kapal',
            'nrp_naik' => 'required|string|max:255',
            'nama_naik' => 'required|string|max:255',
            'jabatan_naik' => 'required|exists:jabatan,id_jabatan',
            'nama_mutasi' => 'required|string|max:255',
            'jenis_mutasi' => 'required|string|max:100',
            'tmt' => 'required|date',
            'tat' => 'required|date|after:tmt',
            'status_abk' => 'required|string',
            'nrp_turun' => 'nullable|string|max:255',
            'nama_turun' => 'nullable|string|max:255',
            'jabatan_turun' => 'nullable|exists:jabatan,id_jabatan',
            'alasan_turun' => 'nullable|string|max:255',
            'keterangan_turun' => 'nullable|string|max:500'
        ]);

        try {
            $mutasi = ABK::findOrFail($id);
            $mutasi->update($request->all());
            
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
    public function mutasiDestroy($id)
    {
        try {
            $mutasi = ABK::findOrFail($id);
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
}
