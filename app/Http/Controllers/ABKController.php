<?php
// filepath: c:\laragon\www\SertijabPelni\app\Http\Controllers\ABKController.php

namespace App\Http\Controllers;

use App\Models\ABKNew;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ABKController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Data statistik ABK saja
            $totalStatistik = [
                'total_abk' => ABKNew::count(),
                'abk_aktif' => ABKNew::where('status_abk', '!=', 'Pensiun')->count(),
                'abk_organik' => ABKNew::where('status_abk', 'Organik')->count(),
                'abk_non_organik' => ABKNew::where('status_abk', 'Non Organik')->count(),
                'abk_pensiun' => ABKNew::where('status_abk', 'Pensiun')->count()
            ];

            // Data ABK per kapal (dummy data untuk sementara)
            $abkPerKapal = collect([
                [
                    'id' => 1,
                    'nama_kapal' => 'KM Sirimau',
                    'total_abk' => 25,
                    'abk_aktif' => 20,
                    'abk_tidak_aktif' => 5
                ],
                [
                    'id' => 2,
                    'nama_kapal' => 'KM Tatamailau',
                    'total_abk' => 30,
                    'abk_aktif' => 28,
                    'abk_tidak_aktif' => 2
                ],
                [
                    'id' => 3,
                    'nama_kapal' => 'KM Dorolonda',
                    'total_abk' => 22,
                    'abk_aktif' => 22,
                    'abk_tidak_aktif' => 0
                ],
                [
                    'id' => 4,
                    'nama_kapal' => 'KM Pangrango',
                    'total_abk' => 35,
                    'abk_aktif' => 32,
                    'abk_tidak_aktif' => 3
                ]
            ]);

            // Data mutasi terbaru (dummy data untuk sementara)
            $mutasiTerbaru = collect([
                [
                    'id' => 1,
                    'abkTurun' => ['nama_abk' => 'Ahmad Syafiq'],
                    'kapalTurun' => ['nama_kapal' => 'KM Sirimau'],
                    'kapalNaik' => ['nama_kapal' => 'KM Tatamailau'],
                    'status_mutasi' => 'Proses',
                    'created_at' => now()->subDays(2)
                ],
                [
                    'id' => 2,
                    'abkTurun' => ['nama_abk' => 'Budi Santoso'],
                    'kapalTurun' => ['nama_kapal' => 'KM Dorolonda'],
                    'kapalNaik' => ['nama_kapal' => 'KM Pangrango'],
                    'status_mutasi' => 'Selesai',
                    'created_at' => now()->subDays(5)
                ],
                [
                    'id' => 3,
                    'abkTurun' => ['nama_abk' => 'Citra Dewi'],
                    'kapalTurun' => ['nama_kapal' => 'KM Tatamailau'],
                    'kapalNaik' => ['nama_kapal' => 'KM Sirimau'],
                    'status_mutasi' => 'Pending',
                    'created_at' => now()->subWeek()
                ]
            ]);

            // Data ABK list terbaru dengan relasi yang benar
            $abkList = ABKNew::with(['jabatanTetap', 'kapalAktif'])
                ->orderBy('created_at', 'desc')
                ->take(20) // Ubah dari 10 menjadi 20 untuk lebih banyak data search
                ->get();

            // Debug data untuk memastikan query bekerja
            Log::info('ABK List Count: ' . $abkList->count());
            if ($abkList->count() > 0) {
                Log::info('Sample ABK: ', $abkList->first()->toArray());
            }
                
            return view('kelolaABK.index', compact(
                'abkList',
                'totalStatistik', 
                'abkPerKapal', 
                'mutasiTerbaru'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading ABK index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
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
            $abkList = collect(); // Empty collection
            
            return view('kelolaABK.index', compact(
                'abkList',
                'totalStatistik', 
                'abkPerKapal', 
                'mutasiTerbaru'
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
            $abk = ABKNew::with('jabatanTetap')->findOrFail($id);
            return view('kelolaABK.show', compact('abk'));
        } catch (\Exception $e) {
            Log::error('Error showing ABK: ' . $e->getMessage());
            return redirect()->route('abk.index')->with('error', 'ABK tidak ditemukan');
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
}
