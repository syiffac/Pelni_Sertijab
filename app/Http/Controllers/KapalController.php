<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use App\Models\ABK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KapalController extends Controller
{
    /**
     * Display a listing of kapal
     */
    public function index()
    {
        $kapalList = Kapal::orderBy('nama_kapal')->get();
        
        $statistics = [
            'total_kapal' => Kapal::count(),
            'total_abk' => ABK::count(),
        ];
        
        return view('kelolaKapal.index', compact('kapalList', 'statistics'));
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
            'jenis_kapal' => 'required|string|max:100',
        ]);

        try {
            Kapal::create([
                'nama_kapal' => $request->nama_kapal,
                'jenis_kapal' => $request->jenis_kapal,
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
        $kapal = Kapal::findOrFail($id);
        return view('kelolaKapal.edit', compact('kapal'));
    }

    /**
     * Update the specified kapal
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kapal' => 'required|string|max:255|unique:kapal,nama_kapal,' . $id,
            'jenis_kapal' => 'required|string|max:100',
        ]);

        try {
            $kapal = Kapal::findOrFail($id);
            $kapal->update([
                'nama_kapal' => $request->nama_kapal,
                'jenis_kapal' => $request->jenis_kapal,
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
