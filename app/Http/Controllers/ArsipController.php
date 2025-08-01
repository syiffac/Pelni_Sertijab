<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kapal;
use App\Models\Sertijab;
use App\Models\Mutasi;
use App\Models\ABK;
use Illuminate\Support\Facades\DB;

class ArsipController extends Controller
{
    /**
     * Display arsip dashboard with kapal selection
     */
    public function index()
    {
        // Ambil data kapal dengan custom count untuk arsip sertijab
        $kapalList = Kapal::with(['mutasiAsal', 'mutasiTujuan'])
            ->get()
            ->map(function ($kapal) {
                // Hitung total arsip dari mutasi asal dan tujuan
                $totalArsipAsal = $kapal->mutasiAsal()
                    ->whereHas('sertijab')
                    ->count();
                
                $totalArsipTujuan = $kapal->mutasiTujuan()
                    ->whereHas('sertijab')
                    ->count();
                
                $finalArsipAsal = $kapal->mutasiAsal()
                    ->whereHas('sertijab', function($query) {
                        $query->where('status_verifikasi', 'verified');
                    })
                    ->count();
                
                $finalArsipTujuan = $kapal->mutasiTujuan()
                    ->whereHas('sertijab', function($query) {
                        $query->where('status_verifikasi', 'verified');
                    })
                    ->count();
                
                $draftArsipAsal = $kapal->mutasiAsal()
                    ->whereHas('sertijab', function($query) {
                        $query->whereIn('status_verifikasi', ['pending', 'rejected']);
                    })
                    ->count();
                
                $draftArsipTujuan = $kapal->mutasiTujuan()
                    ->whereHas('sertijab', function($query) {
                        $query->whereIn('status_verifikasi', ['pending', 'rejected']);
                    })
                    ->count();

                // Set atribut untuk template
                $kapal->total_arsip = $totalArsipAsal + $totalArsipTujuan;
                $kapal->final_arsip = $finalArsipAsal + $finalArsipTujuan;
                $kapal->draft_arsip = $draftArsipAsal + $draftArsipTujuan;
                
                return $kapal;
            });

        // Statistik umum arsip
        $stats = [
            'total_arsip' => Sertijab::count(),
            'final_arsip' => Sertijab::where('status_verifikasi', 'verified')->count(),
            'draft_arsip' => Sertijab::whereIn('status_verifikasi', ['pending', 'rejected'])->count(),
            'pending_verification' => Sertijab::where('status_verifikasi', 'pending')->count(),
            'rejected_documents' => Sertijab::where('status_verifikasi', 'rejected')->count(),
        ];

        // Hitung persentase
        $stats['completion_rate'] = $stats['total_arsip'] > 0 
            ? round(($stats['final_arsip'] / $stats['total_arsip']) * 100) 
            : 0;

        // Data untuk chart bulanan
        $monthlyData = $this->getMonthlyArsipData();

        return view('arsip.index', compact('kapalList', 'stats', 'monthlyData'));
    }

    /**
     * Display arsip search page with filters
     */
    public function search(Request $request)
    {
        $kapalId = $request->input('kapal_id');
        $status = $request->input('status', 'all'); // all, final, draft
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));
        $searchTerm = $request->input('search');

        // Ambil data kapal untuk dropdown
        $kapalList = Kapal::all();

        // Build query
        $query = Sertijab::with(['mutasi.abkTurun', 'mutasi.kapalAsal', 'mutasi.kapalTujuan', 'mutasi.jabatanLama', 'mutasi.jabatanBaru']);

        // Filter berdasarkan kapal
        if ($kapalId) {
            $query->whereHas('mutasi', function($q) use ($kapalId) {
                $q->where('id_kapal_asal', $kapalId)
                  ->orWhere('id_kapal_tujuan', $kapalId);
            });
        }

        // Filter berdasarkan status
        if ($status === 'final') {
            $query->where('status_verifikasi', 'verified');
        } elseif ($status === 'draft') {
            $query->whereIn('status_verifikasi', ['pending', 'rejected']);
        }

        // Filter berdasarkan bulan dan tahun
        if ($bulan) {
            $query->whereMonth('uploaded_at', $bulan);
        }
        if ($tahun) {
            $query->whereYear('uploaded_at', $tahun);
        }

        // Search term (cari berdasarkan nama ABK atau NRP)
        if ($searchTerm) {
            $query->whereHas('mutasi.abkTurun', function($q) use ($searchTerm) {
                $q->where('nama_abk', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('NRP', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Ambil data dengan pagination
        $arsipList = $query->orderBy('uploaded_at', 'desc')->paginate(12);

        return view('arsip.search', compact('kapalList', 'arsipList', 'kapalId', 'status', 'bulan', 'tahun', 'searchTerm'));
    }

    /**
     * Display specific arsip document detail
     */
    public function show($id)
    {
        $sertijab = Sertijab::with(['mutasi.abkTurun', 'mutasi.abkNaik', 'mutasi.kapalAsal', 'mutasi.kapalTujuan', 
                                    'mutasi.jabatanLama', 'mutasi.jabatanBaru'])
                           ->findOrFail($id);

        return view('arsip.show', compact('sertijab'));
    }

    /**
     * Create new arsip entry (manual entry)
     */
    public function create()
    {
        // Ambil data kapal dari table kapal
        $daftarKapal = \App\Models\Kapal::orderBy('nama_kapal')->get()->map(function($kapal) {
            return [
                'id_kapal' => $kapal->id,
                'nama_kapal' => $kapal->nama_kapal,
                'id' => $kapal->id, // Kode kapal sama dengan id
            ];
        });

        // Data dummy untuk jabatan - sama dengan ABK
        $jabatanList = [
            (object)['id_jabatan' => 1, 'nama_jabatan' => 'Nakhoda'],
            (object)['id_jabatan' => 2, 'nama_jabatan' => 'Mualim I'],
            (object)['id_jabatan' => 3, 'nama_jabatan' => 'KKM (Kepala Kamar Mesin)'],
        ];

        return view('arsip.create', compact('daftarKapal', 'jabatanList'));
    }

    /**
     * Store new arsip entry
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_mutasi' => 'required|exists:mutasi,id_mutasi',
            'file_dokumen' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Upload file
        $file = $request->file('file_dokumen');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('sertijab', $fileName, 'public');

        // Create sertijab record
        $sertijab = Sertijab::create([
            'id_mutasi' => $request->id_mutasi,
            'file_path' => $filePath,
            'status_verifikasi' => 'pending', // Default draft
            'keterangan_pengunggah_puk' => $request->keterangan,
            'uploaded_at' => now(),
        ]);

        return redirect()->route('arsip.search')->with('success', 'Dokumen arsip berhasil ditambahkan');
    }

    /**
     * Edit arsip entry
     */
    public function edit($id)
    {
        $sertijab = Sertijab::with(['mutasi.abkTurun', 'mutasi.kapalAsal'])->findOrFail($id);
        
        return view('arsip.edit', compact('sertijab'));
    }

    /**
     * Update arsip entry
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'file_dokumen' => 'nullable|file|mimes:pdf|max:10240',
            'keterangan' => 'nullable|string|max:500',
            'status_verifikasi' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string|max:500',
        ]);

        $sertijab = Sertijab::findOrFail($id);

        // Update file if uploaded
        if ($request->hasFile('file_dokumen')) {
            // Delete old file
            if ($sertijab->file_path && file_exists(storage_path('app/public/' . $sertijab->file_path))) {
                unlink(storage_path('app/public/' . $sertijab->file_path));
            }

            // Upload new file
            $file = $request->file('file_dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('sertijab', $fileName, 'public');
            $sertijab->file_path = $filePath;
        }

        // Update other fields
        $sertijab->keterangan_pengunggah_puk = $request->keterangan;
        $sertijab->status_verifikasi = $request->status_verifikasi;
        $sertijab->notes = $request->notes;

        if ($request->status_verifikasi !== 'pending') {
            $sertijab->verified_by_admin_nrp = auth()->guard('admin')->id() ?? 1;
            $sertijab->verified_at = now();
        }

        $sertijab->save();

        return redirect()->route('arsip.search')->with('success', 'Dokumen arsip berhasil diperbarui');
    }

    /**
     * Delete arsip entry
     */
    public function destroy($id)
    {
        $sertijab = Sertijab::findOrFail($id);

        // Delete file
        if ($sertijab->file_path && file_exists(storage_path('app/public/' . $sertijab->file_path))) {
            unlink(storage_path('app/public/' . $sertijab->file_path));
        }

        $sertijab->delete();

        return redirect()->route('arsip.search')->with('success', 'Dokumen arsip berhasil dihapus');
    }

    /**
     * Generate laporan arsip
     */
    public function laporanIndex()
    {
        $kapalList = Kapal::all();
        $tahunList = Sertijab::selectRaw('YEAR(uploaded_at) as tahun')
                             ->distinct()
                             ->orderBy('tahun', 'desc')
                             ->pluck('tahun');

        return view('arsip.laporan', compact('kapalList', 'tahunList'));
    }

    /**
     * Generate and download laporan
     */
    public function generateLaporan(Request $request)
    {
        $request->validate([
            'kapal_id' => 'nullable|exists:kapal,id_kapal',
            'tahun' => 'required|integer',
            'format' => 'required|in:pdf,excel',
        ]);

        // TODO: Implement laporan generation
        // For now, redirect back with success message
        return redirect()->back()->with('success', 'Laporan berhasil digenerate (fitur dalam pengembangan)');
    }

    /**
     * Get monthly arsip data for chart
     */
    private function getMonthlyArsipData()
    {
        $currentYear = date('Y');
        
        $monthlyData = DB::table('sertijab')
            ->selectRaw('MONTH(uploaded_at) as bulan, COUNT(*) as total')
            ->whereYear('uploaded_at', $currentYear)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        // Fill missing months with 0
        $completeData = [];
        for ($i = 1; $i <= 12; $i++) {
            $completeData[] = $monthlyData->get($i)->total ?? 0;
        }

        return $completeData;
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'sertijab_ids' => 'required|array',
            'sertijab_ids.*' => 'exists:sertijab,id_sertijab',
            'status' => 'required|in:verified,rejected,pending',
            'notes' => 'nullable|string|max:500',
        ]);

        $updated = Sertijab::whereIn('id_sertijab', $request->sertijab_ids)
                          ->update([
                              'status_verifikasi' => $request->status,
                              'notes' => $request->notes,
                              'verified_by_admin_nrp' => auth()->guard('admin')->id() ?? 1,
                              'verified_at' => now(),
                          ]);

        return redirect()->back()->with('success', "Berhasil memperbarui status {$updated} dokumen");
    }
}
