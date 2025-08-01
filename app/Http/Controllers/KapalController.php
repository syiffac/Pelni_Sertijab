<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use App\Models\ABK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KapalController extends Controller
{
    /**
     * Display a listing of kapal
     */
    public function index()
    {
        $kapalList = Kapal::orderBy('nama_kapal')->get();
        
        // Hitung statistik yang relevan dengan kapal
        $statistics = [
            'total_kapal' => Kapal::count(),
            'total_homebase' => Kapal::distinct('home_base')->whereNotNull('home_base')->count('home_base'),
        ];
        
        return view('kelolaKapal.index', compact('kapalList', 'statistics'));
    }

    /**
     * Show the form for creating a new kapal
     */
    public function create()
    {
        // List homebase untuk dropdown - diperluas dan diurutkan
        $homeBaseList = [
            'Ambon',
            'Bau Bau',
            'Benoa',
            'Bitung',
            'Jakarta',
            'Makassar',
            'Surabaya',
            'Kupang'
        ];
        
        // Urutkan berdasarkan abjad
        sort($homeBaseList);
        
        return view('kelolaKapal.create', compact('homeBaseList'));
    }

    /**
     * Store a newly created kapal
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|max:50|unique:kapal,id', // Validasi id sebagai kode kapal
            'nama_kapal' => 'required|string|max:255|unique:kapal,nama_kapal',
            'tipe_pax' => 'nullable|integer|min:0|max:10000',
            'home_base' => 'nullable|string|max:255',
        ], [
            'id.required' => 'Kode kapal wajib diisi',
            'id.unique' => 'Kode kapal sudah terdaftar',
            'nama_kapal.required' => 'Nama kapal wajib diisi',
            'nama_kapal.unique' => 'Nama kapal sudah terdaftar',
            'tipe_pax.integer' => 'Tipe PAX harus berupa angka',
            'tipe_pax.min' => 'Tipe PAX tidak boleh kurang dari 0',
            'tipe_pax.max' => 'Tipe PAX tidak boleh lebih dari 10.000',
        ]);

        try {
            // Mulai transaksi database
            DB::beginTransaction();
            
            // Create kapal baru
            $kapal = Kapal::create([
                'id' => $request->id, // Gunakan id dari input form
                'nama_kapal' => $request->nama_kapal,
                'tipe_pax' => $request->tipe_pax,
                'home_base' => $request->home_base,
            ]);
            
            // Commit transaksi
            DB::commit();
            
            // Log activity
            Log::info('Kapal baru ditambahkan', [
                'id' => $kapal->id,
                'nama_kapal' => $kapal->nama_kapal,
                'user_id' => auth()->guard('admin')->user() ?? 'system'
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data kapal berhasil ditambahkan',
                    'data' => [
                        'id' => $kapal->id,
                        'nama_kapal' => $kapal->nama_kapal
                    ]
                ]);
            }

            return redirect()->route('kapal.index')
                ->with('success', 'Data kapal berhasil ditambahkan');
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();
            
            // Log error
            Log::error('Error menambahkan kapal', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan data kapal: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Gagal menambahkan data kapal: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing kapal
     */
    public function edit($id)
    {
        $kapal = Kapal::findOrFail($id);
        
        // List homebase untuk dropdown
        $homeBaseList = [
            'Jakarta', 'Surabaya', 'Makassar', 'Bitung', 'Kupang', 'Benoa', 'Ambon', 'Bau Bau'
        ];
        
        return view('kelolaKapal.edit', compact('kapal', 'homeBaseList'));
    }

    /**
     * Update the specified kapal
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|string|max:50|unique:kapal,id,' . $id . ',id', // Validasi id tapi exclude id saat ini
            'nama_kapal' => 'required|string|max:255|unique:kapal,nama_kapal,' . $id,
            'tipe_pax' => 'nullable|integer|min:0|max:10000',
            'home_base' => 'nullable|string|max:255',
        ], [
            'id.required' => 'Kode kapal wajib diisi',
            'id.unique' => 'Kode kapal sudah terdaftar',
            'nama_kapal.required' => 'Nama kapal wajib diisi',
            'nama_kapal.unique' => 'Nama kapal sudah terdaftar',
            'tipe_pax.integer' => 'Tipe PAX harus berupa angka',
            'tipe_pax.min' => 'Tipe PAX tidak boleh kurang dari 0',
            'tipe_pax.max' => 'Tipe PAX tidak boleh lebih dari 10.000',
        ]);

        try {
            $kapal = Kapal::findOrFail($id);
            
            // Simpan data lama untuk log
            $oldData = $kapal->toArray();
            
            $kapal->update([
                'id' => $request->id,
                'nama_kapal' => $request->nama_kapal,
                'tipe_pax' => $request->tipe_pax,
                'home_base' => $request->home_base,
            ]);
            
            // Log activity
            Log::info('Kapal diupdate', [
                'id' => $kapal->id,
                'old_data' => $oldData,
                'new_data' => $kapal->toArray(),
                'user_id' => auth()->guard('admin')->user() ?? 'system'
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data kapal berhasil diperbarui'
                ]);
            }

            return redirect()->route('kapal.index')
                ->with('success', 'Data kapal berhasil diperbarui');
        } catch (\Exception $e) {
            // Log error
            Log::error('Error memperbarui kapal', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui data kapal: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Gagal memperbarui data kapal: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified kapal
     */
    public function destroy($id)
    {
        try {
            $kapal = Kapal::findOrFail($id);
            
            // Check dependencies
            $abkCount = $kapal->abk()->count();
            if ($abkCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat menghapus kapal. Masih terdapat {$abkCount} ABK yang terkait dengan kapal ini."
                ], 400);
            }
            
            // Simpan data untuk log
            $kapalData = $kapal->toArray();
            
            $kapal->delete();
            
            // Log activity
            Log::info('Kapal dihapus', [
                'id' => $id,
                'kapal_data' => $kapalData,
                'user_id' => auth()->guard('admin')->user() ?? 'system'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data kapal berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            // Log error
            Log::error('Error menghapus kapal', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data kapal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get kapal data for AJAX requests
     */
    public function getKapal($id)
    {
        try {
            $kapal = Kapal::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $kapal->id,
                    'kode_kapal' => $kapal->kode_kapal,
                    'nama_kapal' => $kapal->nama_kapal,
                    'tipe_pax' => $kapal->tipe_pax,
                    'formatted_tipe_pax' => $kapal->formatted_tipe_pax,
                    'home_base' => $kapal->home_base,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kapal tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Get kapal list for select2/dropdown
     */
    public function getKapalList(Request $request)
    {
        $search = $request->get('q');
        $query = Kapal::orderBy('nama_kapal');
        
        if ($search) {
            $query->where('nama_kapal', 'like', "%{$search}%");
        }
        
        $kapalList = $query->get()->map(function($kapal) {
            return [
                'id' => $kapal->id,
                'text' => $kapal->nama_kapal . ' (' . $kapal->kode_kapal . ')',
                'kode' => $kapal->kode_kapal,
                'tipe_pax' => $kapal->tipe_pax
            ];
        });
        
        return response()->json([
            'results' => $kapalList
        ]);
    }
}
