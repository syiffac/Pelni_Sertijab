<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Mutasi;

class NotificationService
{
    /**
     * Buat notifikasi untuk admin saat PUK submit dokumen
     */
    public static function createSubmitNotification(Mutasi $mutasi)
    {
        // Ambil semua admin
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'type' => 'info',
                'icon' => 'file-earmark-check',
                'title' => 'Dokumen Baru Disubmit',
                'message' => "PUK telah mensubmit dokumen untuk Mutasi MUT-" . str_pad($mutasi->id, 4, '0', STR_PAD_LEFT),
                'link' => route('monitoring.detail', $mutasi->id),
                'read' => false,
                'user_id' => $admin->id,
                'mutasi_id' => $mutasi->id
            ]);
        }
    }

    /**
     * Buat notifikasi untuk admin tentang dokumen yang belum diverifikasi
     */
    public static function createUnverifiedNotification(Mutasi $mutasi)
    {
        // Ambil semua admin
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'type' => 'warning',
                'icon' => 'exclamation-triangle',
                'title' => 'Dokumen Belum Diverifikasi',
                'message' => "Dokumen Mutasi MUT-" . str_pad($mutasi->id, 4, '0', STR_PAD_LEFT) . " menunggu verifikasi",
                'link' => route('monitoring.detail', $mutasi->id),
                'read' => false,
                'user_id' => $admin->id,
                'mutasi_id' => $mutasi->id
            ]);
        }
    }
}