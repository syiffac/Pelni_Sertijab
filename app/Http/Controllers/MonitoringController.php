<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kapal;
use App\Models\ABK;
use App\Models\Mutasi;
use App\Models\Sertijab;
use App\Models\Jabatan;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    /**
     * Display monitoring dashboard
     */
    public function index()
    {
        // Ambil data kapal untuk ditampilkan di dashboard
        $kapalList = Kapal::withCount(['abk'])->get();
        
        // Statistik ringkasan untuk dashboard
        $stats = [
            'total_abk' => ABK::count(),
            'total_mutasi' => Mutasi::where('perlu_sertijab', true)->count(),
            'sertijab_submitted' => Sertijab::count(),
            'sertijab_verified' => Sertijab::where('status_verifikasi', 'verified')->count(),
            'sertijab_pending' => Sertijab::where('status_verifikasi', 'pending')->count(),
            'sertijab_rejected' => Sertijab::where('status_verifikasi', 'rejected')->count(),
        ];
        
        // Hitung persentase progress verifikasi
        $stats['verification_progress'] = $stats['total_mutasi'] > 0 
            ? round(($stats['sertijab_verified'] / $stats['total_mutasi']) * 100) 
            : 0;
            
        // Hitung persentase progress submission
        $stats['submission_progress'] = $stats['total_mutasi'] > 0 
            ? round(($stats['sertijab_submitted'] / $stats['total_mutasi']) * 100) 
            : 0;
            
        // Ambil data monitoring per kapal
        $monitoringData = $this->getMonitoringOverview();
        
        return view('monitoring.index', compact('kapalList', 'stats', 'monitoringData'));
    }
    
    /**
     * Display sertijab monitoring by kapal
     */
    public function sertijab(Request $request)
    {
        // Filter by kapal_id jika ada
        $kapalId = $request->input('kapal_id');
        
        // Ambil data kapal untuk dropdown filter
        $kapalList = Kapal::all();
        
        // Query data mutasi yang perlu sertijab
        $query = Mutasi::with(['abkTurun', 'abkNaik', 'kapalAsal', 'kapalTujuan', 
                               'jabatanLama', 'jabatanBaru', 'sertijab'])
                      ->where('perlu_sertijab', true);
        
        // Filter berdasarkan kapal jika dipilih
        if ($kapalId) {
            $query->where(function($q) use ($kapalId) {
                $q->where('id_kapal_asal', $kapalId)
                  ->orWhere('id_kapal_tujuan', $kapalId);
            });
        }
        
        // Ambil data
        $mutasiList = $query->orderBy('TMT', 'desc')->paginate(10);
        
        return view('monitoring.sertijab', compact('kapalList', 'mutasiList', 'kapalId'));
    }
    
    /**
     * Display sertijab detail
     */
    public function sertijabDetail($id)
    {
        // Ambil detail mutasi dengan relasi
        $mutasi = Mutasi::with(['abkTurun', 'abkNaik', 'kapalAsal', 'kapalTujuan', 
                                'jabatanLama', 'jabatanBaru', 'sertijab'])
                        ->findOrFail($id);
        
        return view('monitoring.detail', compact('mutasi'));
    }
    
    /**
     * Verify sertijab document
     */
    public function verifySertijab(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:verified,rejected',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $sertijab = Sertijab::findOrFail($id);
        $sertijab->status_verifikasi = $request->status_verifikasi;
        $sertijab->notes = $request->notes;
        $sertijab->verified_by_admin_nrp = auth()->guard('admin')->user()->NRP_admin;
        $sertijab->verified_at = now();
        $sertijab->save();
        
        return redirect()->back()->with('success', 'Dokumen Sertijab berhasil diverifikasi');
    }
    
    /**
     * Export sertijab monitoring data
     */
    public function exportSertijab(Request $request)
    {
        // Implementation for exporting data
        // ...
        
        return redirect()->back()->with('success', 'Data Sertijab berhasil diekspor');
    }
    
    /**
     * Helper function to get monitoring overview data
     */
    private function getMonitoringOverview()
    {
        $monitoringData = DB::table('kapal')
            ->select(
                'kapal.id',
                'kapal.nama_kapal',
                DB::raw('COUNT(DISTINCT mutasi.id) as total_mutasi'),
                DB::raw('COUNT(DISTINCT CASE WHEN sertijab.id IS NOT NULL THEN mutasi.id END) as submitted'),
                DB::raw('COUNT(DISTINCT CASE WHEN sertijab.status_verifikasi = "verified" THEN mutasi.id END) as verified'),
                DB::raw('COUNT(DISTINCT CASE WHEN sertijab.status_verifikasi = "rejected" THEN mutasi.id END) as rejected'),
                DB::raw('COUNT(DISTINCT CASE WHEN sertijab.status_verifikasi = "pending" THEN mutasi.id END) as pending')
            )
            ->leftJoin('mutasi', function($join) {
                $join->on('kapal.id', '=', 'mutasi.id_kapal_asal')
                     ->orOn('kapal.id', '=', 'mutasi.id_kapal_tujuan');
            })
            ->leftJoin('sertijab', 'mutasi.id', '=', 'sertijab.id')
            ->where('mutasi.perlu_sertijab', true)
            ->groupBy('kapal.id', 'kapal.nama_kapal')
            ->get();
        
        return $monitoringData;
    }
}
