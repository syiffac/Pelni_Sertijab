<?php

namespace App\Http\Controllers;

use App\Models\ABK;
use App\Models\Kapal;
use App\Models\Jabatan;
use App\Models\Mutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get real data from database
            $totalStatistik = [
                'total_abk' => ABK::count(),
                'sertijab_bulan_ini' => Mutasi::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'menunggu_verifikasi' => Mutasi::where('status_mutasi', 'Menunggu')->count(),
                'total_arsip' => Mutasi::where('status_mutasi', 'Selesai')->count(),
                'total_kapal' => Kapal::count(),
                'abk_aktif' => ABK::where('status_abk', 'Aktif')->count(),
            ];

            // Recent activities with real data
            $recentActivities = [
                // Recent ABK additions
                ...ABK::with(['kapal', 'jabatan'])
                    ->latest()
                    ->take(2)
                    ->get()
                    ->map(function ($abk) {
                        return [
                            'type' => 'abk_new',
                            'title' => 'ABK baru ' . $abk->nama_abk . ' ditambahkan ke sistem',
                            'subtitle' => 'Kapal: ' . $abk->kapal->nama_kapal ?? 'Unknown',
                            'time' => $abk->created_at->diffForHumans(),
                            'icon' => 'person-plus',
                            'color' => 'primary',
                            'badge' => 'ABK Baru'
                        ];
                    })->toArray(),
                
                // Recent mutations
                ...Mutasi::with(['abkTurun', 'kapalTurun', 'kapalNaik'])
                    ->latest()
                    ->take(3)
                    ->get()
                    ->map(function ($mutasi) {
                        $status_color = match($mutasi->status_mutasi) {
                            'Selesai' => 'success',
                            'Proses' => 'warning',
                            default => 'info'
                        };
                        
                        return [
                            'type' => 'mutasi',
                            'title' => 'Mutasi ' . ($mutasi->abkTurun->nama_abk ?? 'ABK') . ' telah ' . strtolower($mutasi->status_mutasi),
                            'subtitle' => 'Dari ' . ($mutasi->kapalTurun->nama_kapal ?? 'N/A') . ' ke ' . ($mutasi->kapalNaik->nama_kapal ?? 'N/A'),
                            'time' => $mutasi->created_at->diffForHumans(),
                            'icon' => $mutasi->status_mutasi == 'Selesai' ? 'check-circle' : 'hourglass-split',
                            'color' => $status_color,
                            'badge' => $mutasi->status_mutasi
                        ];
                    })->toArray()
            ];

            // Sort by created time and take only 4 most recent
            usort($recentActivities, function($a, $b) {
                return strcmp($b['time'], $a['time']);
            });
            $recentActivities = array_slice($recentActivities, 0, 4);

            return view('dashboard', compact('totalStatistik', 'recentActivities'));
        } catch (\Exception $e) {
            // Fallback to mock data if database error
            $mockData = [
                'total_abk' => '1,245',
                'sertijab_bulan_ini' => '89',
                'menunggu_verifikasi' => '23',
                'total_arsip' => '3,567'
            ];
            
            return view('dashboard', compact('mockData'));
        }
    }
}
