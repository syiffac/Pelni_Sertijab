<?php

namespace App\Http\Controllers;

use App\Models\ABKNew;
use App\Models\Kapal;
use App\Models\Jabatan;
use App\Models\Mutasi;
use App\Models\Sertijab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // ===== STATISTIK UTAMA =====
            $totalStatistik = $this->getTotalStatistik();
            
            // ===== RECENT ACTIVITIES =====
            $recentActivities = $this->getRecentActivities();
            
            // ===== CHART DATA =====
            $chartData = $this->getChartData();
            
            // ===== SUMMARY DATA =====
            $summaryData = $this->getSummaryData();
            
            // ===== QUICK STATS =====
            $quickStats = $this->getQuickStats();

            return view('dashboard', compact(
                'totalStatistik', 
                'recentActivities', 
                'chartData', 
                'summaryData',
                'quickStats'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading dashboard: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Fallback data jika ada error
            return $this->getFallbackDashboard($e);
        }
    }
    
    /**
     * Get comprehensive statistics from all modules
     */
    private function getTotalStatistik()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth();
        
        // ABK Statistics
        $totalAbk = ABKNew::count();
        $abkAktif = ABKNew::where('status_abk', '!=', 'Pensiun')->count();
        $abkOrganik = ABKNew::where('status_abk', 'Organik')->count();
        $abkNonOrganik = ABKNew::where('status_abk', 'Non Organik')->count();
        $abkPensiun = ABKNew::where('status_abk', 'Pensiun')->count();
        
        // Kapal Statistics  
        $totalKapal = Kapal::count();
        $kapalAktif = Kapal::whereHas('abk')->count();
        
        // Mutasi Statistics
        $totalMutasi = Mutasi::count();
        $mutasiBulanIni = Mutasi::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        $mutasiBulanLalu = Mutasi::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        $mutasiSelesai = Mutasi::where('status_mutasi', 'Selesai')->count();
        $mutasiProses = Mutasi::whereIn('status_mutasi', ['Draft', 'Disetujui', 'Proses'])->count();
        
        // Sertijab Statistics
        $totalSertijab = Sertijab::count();
        $sertijabSubmitted = Sertijab::whereNotNull('submitted_at')->count();
        $sertijabVerified = Sertijab::where('status_dokumen', 'final')->count();
        $sertijabPending = Sertijab::where('status_dokumen', 'draft')->count();
        $sertijabPartial = Sertijab::where('status_dokumen', 'partial')->count();
        
        // Calculate trends
        $mutasiTrend = $mutasiBulanLalu > 0 
            ? round((($mutasiBulanIni - $mutasiBulanLalu) / $mutasiBulanLalu) * 100, 1)
            : ($mutasiBulanIni > 0 ? 100 : 0);
            
        $verifikasiProgress = $sertijabSubmitted > 0 
            ? round(($sertijabVerified / $sertijabSubmitted) * 100, 1)
            : 0;
            
        $submissionProgress = $totalMutasi > 0 
            ? round(($sertijabSubmitted / $totalMutasi) * 100, 1)
            : 0;
        
        return [
            // Main stats for cards
            'total_abk' => $totalAbk,
            'sertijab_bulan_ini' => $mutasiBulanIni,
            'menunggu_verifikasi' => $sertijabPending + $sertijabPartial,
            'total_arsip' => $sertijabVerified,
            
            // Additional detailed stats
            'abk_aktif' => $abkAktif,
            'abk_organik' => $abkOrganik,
            'abk_non_organik' => $abkNonOrganik,
            'abk_pensiun' => $abkPensiun,
            'total_kapal' => $totalKapal,
            'kapal_aktif' => $kapalAktif,
            'total_mutasi' => $totalMutasi,
            'mutasi_selesai' => $mutasiSelesai,
            'mutasi_proses' => $mutasiProses,
            'total_sertijab' => $totalSertijab,
            'sertijab_submitted' => $sertijabSubmitted,
            'sertijab_verified' => $sertijabVerified,
            'sertijab_pending' => $sertijabPending,
            'sertijab_partial' => $sertijabPartial,
            
            // Trends and progress
            'mutasi_trend' => $mutasiTrend,
            'verifikasi_progress' => $verifikasiProgress,
            'submission_progress' => $submissionProgress,
            'efisiensi_proses' => $totalMutasi > 0 ? round(($mutasiSelesai / $totalMutasi) * 100, 1) : 0,
        ];
    }
    
    /**
     * Get recent activities from all modules
     */
    private function getRecentActivities()
    {
        $activities = collect();
        
        try {
            // Recent ABK additions (last 7 days)
            $recentAbk = ABKNew::with(['jabatanTetap'])
                ->where('created_at', '>=', now()->subDays(7))
                ->latest()
                ->take(3)
                ->get();
                
            foreach ($recentAbk as $abk) {
                $activities->push([
                    'type' => 'abk_new',
                    'title' => "ABK baru {$abk->nama_abk} ditambahkan ke sistem",
                    'subtitle' => "Jabatan: " . ($abk->jabatanTetap->nama_jabatan ?? 'Unknown'),
                    'time' => $abk->created_at->diffForHumans(),
                    'icon' => 'person-plus',
                    'color' => 'primary',
                    'badge' => 'ABK Baru',
                    'created_at' => $abk->created_at
                ]);
            }
            
            // Recent mutations (last 7 days)
            $recentMutasi = Mutasi::with(['kapal', 'abkNaik', 'abkTurun'])
                ->where('created_at', '>=', now()->subDays(7))
                ->latest()
                ->take(4)
                ->get();
                
            foreach ($recentMutasi as $mutasi) {
                $statusColor = match($mutasi->status_mutasi) {
                    'Selesai' => 'success',
                    'Proses', 'Disetujui' => 'warning',
                    'Draft' => 'info',
                    default => 'secondary'
                };
                
                $icon = match($mutasi->status_mutasi) {
                    'Selesai' => 'check-circle',
                    'Proses', 'Disetujui' => 'hourglass-split',
                    'Draft' => 'file-plus',
                    default => 'arrow-repeat'
                };
                
                $activities->push([
                    'type' => 'mutasi',
                    'title' => "Mutasi ABK {$mutasi->nama_lengkap_naik} telah " . strtolower($mutasi->status_mutasi),
                    'subtitle' => "Kapal: " . ($mutasi->kapal->nama_kapal ?? 'Unknown') . " - " . ($mutasi->nama_mutasi ?? 'Mutasi'),
                    'time' => $mutasi->created_at->diffForHumans(),
                    'icon' => $icon,
                    'color' => $statusColor,
                    'badge' => $mutasi->status_mutasi,
                    'created_at' => $mutasi->created_at
                ]);
            }
            
            // Recent sertijab submissions (last 7 days)
            $recentSertijab = Sertijab::with(['mutasi.kapal', 'mutasi.abkNaik'])
                ->where('submitted_at', '>=', now()->subDays(7))
                ->latest('submitted_at')
                ->take(3)
                ->get();
                
            foreach ($recentSertijab as $sertijab) {
                $statusColor = match($sertijab->status_dokumen) {
                    'final' => 'success',
                    'partial' => 'warning',
                    'draft' => 'info',
                    default => 'secondary'
                };
                
                $statusText = match($sertijab->status_dokumen) {
                    'final' => 'terverifikasi',
                    'partial' => 'sebagian terverifikasi',
                    'draft' => 'menunggu verifikasi',
                    default => 'disubmit'
                };
                
                $activities->push([
                    'type' => 'sertijab',
                    'title' => "Dokumen sertijab ABK {$sertijab->mutasi->nama_lengkap_naik} {$statusText}",
                    'subtitle' => "Kapal: " . ($sertijab->mutasi->kapal->nama_kapal ?? 'Unknown'),
                    'time' => $sertijab->submitted_at->diffForHumans(),
                    'icon' => $sertijab->status_dokumen === 'final' ? 'check-circle' : 'file-earmark-check',
                    'color' => $statusColor,
                    'badge' => ucfirst($statusText),
                    'created_at' => $sertijab->submitted_at
                ]);
            }
            
            // Recent kapal additions (if any in last 30 days)
            $recentKapal = Kapal::where('created_at', '>=', now()->subDays(30))
                ->latest()
                ->take(2)
                ->get();
                
            foreach ($recentKapal as $kapal) {
                $activities->push([
                    'type' => 'kapal_new',
                    'title' => "Kapal baru {$kapal->nama_kapal} ditambahkan",
                    'subtitle' => "Home Base: " . ($kapal->home_base ?? 'Not Set') . " - PAX: " . ($kapal->tipe_pax ?? 0),
                    'time' => $kapal->created_at->diffForHumans(),
                    'icon' => 'ship',
                    'color' => 'info',
                    'badge' => 'Kapal Baru',
                    'created_at' => $kapal->created_at
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error getting recent activities: ' . $e->getMessage());
        }
        
        // Sort by creation time and take only the most recent 5
        $activities = $activities->sortByDesc('created_at')->take(5);
        
        // Add default activity if no recent activities
        if ($activities->isEmpty()) {
            $activities->push([
                'type' => 'system',
                'title' => 'Sistem Sertijab PELNI siap digunakan',
                'subtitle' => 'Semua modul telah diintegrasikan dan berfungsi dengan baik',
                'time' => 'Baru saja',
                'icon' => 'check-circle',
                'color' => 'success',
                'badge' => 'System Ready',
                'created_at' => now()
            ]);
        }
        
        return $activities->values()->all();
    }
    
    /**
     * Get chart data for dashboard
     */
    private function getChartData()
    {
        try {
            // Monthly mutasi data for the current year
            $monthlyMutasi = $this->getMonthlyMutasiData();
            
            // ABK status distribution
            $abkStatusData = $this->getAbkStatusDistribution();
            
            // Sertijab verification progress
            $verificationData = $this->getVerificationProgressData();
            
            // Kapal with most mutasi
            $kapalMutasiData = $this->getKapalMutasiData();
            
            return [
                'monthly_mutasi' => $monthlyMutasi,
                'abk_status' => $abkStatusData,
                'verification_progress' => $verificationData,
                'kapal_mutasi' => $kapalMutasiData,
            ];
            
        } catch (\Exception $e) {
            Log::error('Error getting chart data: ' . $e->getMessage());
            return [
                'monthly_mutasi' => array_fill(0, 12, 0),
                'abk_status' => [],
                'verification_progress' => [],
                'kapal_mutasi' => [],
            ];
        }
    }
    
    /**
     * Get monthly mutasi data
     */
    private function getMonthlyMutasiData()
    {
        $currentYear = now()->year;
        
        $monthlyData = Mutasi::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');
        
        // Fill all 12 months
        $completeData = [];
        for ($i = 1; $i <= 12; $i++) {
            $completeData[] = $monthlyData->get($i)->total ?? 0;
        }
        
        return $completeData;
    }
    
    /**
     * Get ABK status distribution
     */
    private function getAbkStatusDistribution()
    {
        return ABKNew::selectRaw('status_abk, COUNT(*) as total')
            ->groupBy('status_abk')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->status_abk,
                    'value' => $item->total,
                    'color' => match($item->status_abk) {
                        'Organik' => '#10b981',
                        'Non Organik' => '#f59e0b', 
                        'Pensiun' => '#ef4444',
                        default => '#6b7280'
                    }
                ];
            })->toArray();
    }
    
    /**
     * Get verification progress data
     */
    private function getVerificationProgressData()
    {
        return Sertijab::selectRaw('status_dokumen, COUNT(*) as total')
            ->groupBy('status_dokumen')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => match($item->status_dokumen) {
                        'final' => 'Terverifikasi',
                        'partial' => 'Sebagian Terverifikasi',
                        'draft' => 'Menunggu Verifikasi',
                        default => 'Unknown'
                    },
                    'value' => $item->total,
                    'color' => match($item->status_dokumen) {
                        'final' => '#10b981',
                        'partial' => '#f59e0b',
                        'draft' => '#3b82f6',
                        default => '#6b7280'
                    }
                ];
            })->toArray();
    }
    
    /**
     * Get kapal with most mutasi
     */
    private function getKapalMutasiData()
    {
        return Mutasi::with('kapal')
            ->selectRaw('id_kapal, COUNT(*) as total_mutasi')
            ->groupBy('id_kapal')
            ->orderBy('total_mutasi', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'kapal' => $item->kapal->nama_kapal ?? 'Unknown',
                    'total' => $item->total_mutasi
                ];
            })->toArray();
    }
    
    /**
     * Get summary data for dashboard cards
     */
    private function getSummaryData()
    {
        try {
            return [
                // Top performing metrics
                'top_kapal_mutasi' => Mutasi::with('kapal')
                    ->selectRaw('id_kapal, COUNT(*) as total')
                    ->groupBy('id_kapal')
                    ->orderBy('total', 'desc')
                    ->first(),
                    
                'most_common_jabatan' => ABKNew::with('jabatanTetap')
                    ->selectRaw('id_jabatan_tetap, COUNT(*) as total')
                    ->groupBy('id_jabatan_tetap')
                    ->orderBy('total', 'desc')
                    ->first(),
                    
                'completion_rate' => $this->getCompletionRate(),
                'efficiency_metrics' => $this->getEfficiencyMetrics(),
            ];
            
        } catch (\Exception $e) {
            Log::error('Error getting summary data: ' . $e->getMessage());
            return [
                'top_kapal_mutasi' => null,
                'most_common_jabatan' => null,
                'completion_rate' => 0,
                'efficiency_metrics' => []
            ];
        }
    }
    
    /**
     * Get completion rate metrics
     */
    private function getCompletionRate()
    {
        $totalMutasi = Mutasi::count();
        if ($totalMutasi === 0) return 0;
        
        $completedMutasi = Mutasi::where('status_mutasi', 'Selesai')->count();
        return round(($completedMutasi / $totalMutasi) * 100, 1);
    }
    
    /**
     * Get efficiency metrics
     */
    private function getEfficiencyMetrics()
    {
        $totalSertijab = Sertijab::count();
        $verifiedSertijab = Sertijab::where('status_dokumen', 'final')->count();
        $avgProcessingTime = $this->getAverageProcessingTime();
        
        return [
            'verification_rate' => $totalSertijab > 0 ? round(($verifiedSertijab / $totalSertijab) * 100, 1) : 0,
            'avg_processing_days' => $avgProcessingTime,
            'pending_items' => Sertijab::where('status_dokumen', 'draft')->count(),
        ];
    }
    
    /**
     * Get average processing time
     */
    private function getAverageProcessingTime()
    {
        try {
            $avgTime = Sertijab::whereNotNull('submitted_at')
                ->whereNotNull('verified_at')
                ->selectRaw('AVG(DATEDIFF(verified_at, submitted_at)) as avg_days')
                ->first();
                
            return $avgTime ? round($avgTime->avg_days, 1) : 0;
            
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get quick stats for sidebar or widgets
     */
    private function getQuickStats()
    {
        try {
            return [
                'pending_approvals' => Mutasi::where('status_mutasi', 'Draft')->count(),
                'today_submissions' => Sertijab::whereDate('submitted_at', today())->count(),
                'active_processes' => Mutasi::whereIn('status_mutasi', ['Proses', 'Disetujui'])->count(),
                'completion_this_month' => Mutasi::where('status_mutasi', 'Selesai')
                    ->whereMonth('updated_at', now()->month)
                    ->whereYear('updated_at', now()->year)
                    ->count(),
            ];
            
        } catch (\Exception $e) {
            Log::error('Error getting quick stats: ' . $e->getMessage());
            return [
                'pending_approvals' => 0,
                'today_submissions' => 0,
                'active_processes' => 0,
                'completion_this_month' => 0,
            ];
        }
    }
    
    /**
     * Fallback dashboard data if main query fails
     */
    private function getFallbackDashboard($exception)
    {
        Log::error('Using fallback dashboard data due to error: ' . $exception->getMessage());
        
        $totalStatistik = [
            'total_abk' => 0,
            'sertijab_bulan_ini' => 0,
            'menunggu_verifikasi' => 0,
            'total_arsip' => 0,
            'mutasi_trend' => 0,
            'verifikasi_progress' => 0,
        ];
        
        $recentActivities = [[
            'type' => 'system_error',
            'title' => 'Sistem sedang dalam pemeliharaan',
            'subtitle' => 'Data akan segera tersedia setelah pemeliharaan selesai',
            'time' => 'Baru saja',
            'icon' => 'tools',
            'color' => 'warning',
            'badge' => 'Maintenance'
        ]];
        
        $chartData = [
            'monthly_mutasi' => array_fill(0, 12, 0),
            'abk_status' => [],
            'verification_progress' => [],
            'kapal_mutasi' => [],
        ];
        
        $summaryData = [
            'top_kapal_mutasi' => null,
            'most_common_jabatan' => null,
            'completion_rate' => 0,
            'efficiency_metrics' => []
        ];
        
        $quickStats = [
            'pending_approvals' => 0,
            'today_submissions' => 0,
            'active_processes' => 0,
            'completion_this_month' => 0,
        ];
        
        return view('dashboard', compact(
            'totalStatistik', 
            'recentActivities', 
            'chartData', 
            'summaryData',
            'quickStats'
        ))->with('error', 'Terjadi kesalahan saat memuat data dashboard. Silakan coba lagi nanti.');
    }
    
    /**
     * API endpoint for real-time dashboard updates
     */
    public function getRealtimeData(Request $request)
    {
        try {
            $type = $request->get('type', 'all');
            
            $data = [];
            
            if ($type === 'all' || $type === 'stats') {
                $data['stats'] = $this->getTotalStatistik();
            }
            
            if ($type === 'all' || $type === 'activities') {
                $data['activities'] = $this->getRecentActivities();
            }
            
            if ($type === 'all' || $type === 'charts') {
                $data['charts'] = $this->getChartData();
            }
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting realtime data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data real-time'
            ], 500);
        }
    }
}
