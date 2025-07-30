<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KapalController extends Controller
{
    /**
     * Display a listing of kapal
     */
    public function index()
    {
        try {
            $kapalList = Kapal::orderBy('nama_kapal')->get();
            
            $statistics = [
                'total_kapal' => Kapal::count(),
                'kapal_aktif' => Kapal::where('status_kapal', 'Aktif')->count(),
                'kapal_tidak_aktif' => Kapal::where('status_kapal', '!=', 'Aktif')->count(),
            ];
            
            return view('kelolaKapal.index', compact('kapalList', 'statistics'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new kapal
     */
    public function create()
    {
        return view('kelolaKapal.create');
    }

    /**
     * Store a newly created kapal
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kapal' => 'required|string|max:255|unique:kapal,nama_kapal',
            'kode_kapal' => 'required|string|max:50|unique:kapal,kode_kapal',
            'jenis_kapal' => 'required|string|max:100',
            'tahun_pembuatan' => 'nullable|integer|min:1900|max:' . date('Y'),
            'status_kapal' => 'required|in:Aktif,Tidak Aktif,Maintenance',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            Kapal::create([
                'nama_kapal' => $request->nama_kapal,
                'kode_kapal' => strtoupper($request->kode_kapal),
                'jenis_kapal' => $request->jenis_kapal,
                'tahun_pembuatan' => $request->tahun_pembuatan,
                'status_kapal' => $request->status_kapal,
                'keterangan' => $request->keterangan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data kapal berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data kapal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing kapal
     */
    public function edit($id)
    {
        try {
            $kapal = Kapal::findOrFail($id);
            return view('kelolaKapal.edit', compact('kapal'));
        } catch (\Exception $e) {
            return back()->with('error', 'Kapal tidak ditemukan');
        }
    }

    /**
     * Update the specified kapal
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kapal' => 'required|string|max:255|unique:kapal,nama_kapal,' . $id . ',id_kapal',
            'kode_kapal' => 'required|string|max:50|unique:kapal,kode_kapal,' . $id . ',id_kapal',
            'jenis_kapal' => 'required|string|max:100',
            'tahun_pembuatan' => 'nullable|integer|min:1900|max:' . date('Y'),
            'status_kapal' => 'required|in:Aktif,Tidak Aktif,Maintenance',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            $kapal = Kapal::findOrFail($id);
            $kapal->update([
                'nama_kapal' => $request->nama_kapal,
                'kode_kapal' => strtoupper($request->kode_kapal),
                'jenis_kapal' => $request->jenis_kapal,
                'tahun_pembuatan' => $request->tahun_pembuatan,
                'status_kapal' => $request->status_kapal,
                'keterangan' => $request->keterangan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data kapal berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data kapal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified kapal
     */
    public function destroy($id)
    {
        try {
            $kapal = Kapal::findOrFail($id);
            
            // Check if kapal has ABK
            $hasABK = DB::table('abk')->where('id_kapal', $id)->exists();
            
            if ($hasABK) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kapal tidak dapat dihapus karena masih memiliki ABK'
                ], 400);
            }

            $kapal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data kapal berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data kapal: ' . $e->getMessage()
            ], 500);
        }
    }
}
