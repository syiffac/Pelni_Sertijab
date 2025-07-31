<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kapal;
use App\Models\Mutasi;
use App\Models\ABK;
use App\Models\Sertijab;
use Illuminate\Support\Facades\Storage;

class PUKController extends Controller
{
    public function uploadForm()
    {
        $kapals = Kapal::orderBy('nama_kapal')->get();
        return view('puk.upload-form', compact('kapals'));
    }

    public function getMutasiByKapal(Request $request)
    {
        $request->validate([
            'id_kapal' => 'required|exists:kapal,id_kapal'
        ]);

        $kapal = Kapal::findOrFail($request->id_kapal);
        
        // Ambil data mutasi yang belum ada dokumen sertijab
        $mutasis = Mutasi::with(['abkTurun', 'abkNaik', 'kapalAsal', 'kapalTujuan', 'jabatanLama', 'jabatanBaru'])
            ->where(function($query) use ($request) {
                $query->where('id_kapal_asal', $request->id_kapal)
                      ->orWhere('id_kapal_tujuan', $request->id_kapal);
            })
            ->whereDoesntHave('sertijab')
            ->orderBy('TMT', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'kapal' => $kapal,
            'mutasis' => $mutasis->map(function($mutasi) {
                return [
                    'id' => $mutasi->id,
                    'TMT' => $mutasi->TMT,
                    'keterangan' => $mutasi->keterangan,
                    'abk_turun' => [
                        'NRP' => $mutasi->NRP_abk_turun,
                        'nama' => $mutasi->abkTurun ? $mutasi->abkTurun->nama_abk : 'N/A',
                    ],
                    'abk_naik' => $mutasi->NRP_abk_naik ? [
                        'NRP' => $mutasi->NRP_abk_naik,
                        'nama' => $mutasi->abkNaik ? $mutasi->abkNaik->nama_abk : 'N/A',
                    ] : null,
                    'kapal_asal' => $mutasi->kapalAsal ? $mutasi->kapalAsal->nama_kapal : 'N/A',
                    'kapal_tujuan' => $mutasi->kapalTujuan ? $mutasi->kapalTujuan->nama_kapal : 'N/A',
                    'jabatan_lama' => $mutasi->jabatanLama ? $mutasi->jabatanLama->nama_jabatan : 'N/A',
                    'jabatan_baru' => $mutasi->jabatanBaru ? $mutasi->jabatanBaru->nama_jabatan : 'N/A',
                ];
            })
        ]);
    }

    public function uploadSertijab(Request $request)
    {
        $request->validate([
            'id_mutasi' => 'required|exists:mutasi,id',
            'file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'keterangan_puk' => 'nullable|string|max:1000'
        ]);

        try {
            $mutasi = Mutasi::findOrFail($request->id_mutasi);
            
            // Check if sertijab already exists
            if ($mutasi->sertijab) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen sertijab untuk mutasi ini sudah ada.'
                ]);
            }

            // Generate unique filename
            $file = $request->file('file');
            $filename = time() . '_' . $mutasi->id . '_' . $file->getClientOriginalName();
            
            // Store file
            $path = $file->storeAs('sertijab', $filename, 'public');

            // Create sertijab record
            $sertijab = Sertijab::create([
                'id_mutasi' => $mutasi->id,
                'file_path' => $path,
                'status_verifikasi' => 'pending',
                'keterangan_pengunggah_puk' => $request->keterangan_puk,
                'uploaded_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen sertijab berhasil diunggah.',
                'sertijab' => $sertijab
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteSertijab(Request $request)
    {
        $request->validate([
            'id_sertijab' => 'required|exists:sertijab,id_sertijab'
        ]);

        try {
            $sertijab = Sertijab::findOrFail($request->id_sertijab);
            
            // Delete file from storage
            if (Storage::disk('public')->exists($sertijab->file_path)) {
                Storage::disk('public')->delete($sertijab->file_path);
            }

            // Delete record
            $sertijab->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen sertijab berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
