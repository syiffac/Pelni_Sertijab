<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Mutasi;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Buat notifikasi untuk admin saat PUK submit dokumen
     */
    public static function createSubmitNotification(Mutasi $mutasi)
    {
        try {
            Log::info('Starting createSubmitNotification for mutasi ID: ' . $mutasi->id);
            
            // Ambil semua admin
            $admins = User::where('role', 'admin')->get();
            Log::info('Found ' . $admins->count() . ' admin users');
            
            if ($admins->isEmpty()) {
                Log::warning('No admin users found to send notifications');
                return;
            }
            
            foreach ($admins as $admin) {
                $notification = Notification::create([
                    'type' => 'info',
                    'icon' => 'file-earmark-check',
                    'title' => 'Dokumen Baru Disubmit',
                    'message' => "PUK telah mensubmit dokumen untuk Mutasi MUT-" . str_pad($mutasi->id, 4, '0', STR_PAD_LEFT),
                    'link' => route('monitoring.detail', $mutasi->id),
                    'read' => false,
                    'user_id' => $admin->id,
                    'mutasi_id' => $mutasi->id
                ]);
                
                Log::info('Created notification ID ' . $notification->id . ' for admin ID ' . $admin->id);
            }
            
            Log::info('Successfully created submit notifications for mutasi ID: ' . $mutasi->id);
            
        } catch (\Exception $e) {
            Log::error('Error in createSubmitNotification: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Buat notifikasi untuk admin tentang dokumen yang belum diverifikasi
     */
    public static function createUnverifiedNotification(Mutasi $mutasi)
    {
        try {
            Log::info('Starting createUnverifiedNotification for mutasi ID: ' . $mutasi->id);
            
            // Ambil semua admin
            $admins = User::where('role', 'admin')->get();
            Log::info('Found ' . $admins->count() . ' admin users');
            
            if ($admins->isEmpty()) {
                Log::warning('No admin users found to send unverified notifications');
                return;
            }
            
            foreach ($admins as $admin) {
                // Cek apakah notifikasi unverified untuk mutasi ini sudah ada
                $existingNotification = Notification::where('user_id', $admin->id)
                    ->where('mutasi_id', $mutasi->id)
                    ->where('type', 'warning')
                    ->where('title', 'Dokumen Belum Diverifikasi')
                    ->first();
                    
                if (!$existingNotification) {
                    $notification = Notification::create([
                        'type' => 'warning',
                        'icon' => 'exclamation-triangle',
                        'title' => 'Dokumen Belum Diverifikasi',
                        'message' => "Dokumen Mutasi MUT-" . str_pad($mutasi->id, 4, '0', STR_PAD_LEFT) . " menunggu verifikasi",
                        'link' => route('monitoring.detail', $mutasi->id),
                        'read' => false,
                        'user_id' => $admin->id,
                        'mutasi_id' => $mutasi->id
                    ]);
                    
                    Log::info('Created unverified notification ID ' . $notification->id . ' for admin ID ' . $admin->id);
                } else {
                    Log::info('Unverified notification already exists for admin ID ' . $admin->id . ' and mutasi ID ' . $mutasi->id);
                }
            }
            
            Log::info('Successfully processed unverified notifications for mutasi ID: ' . $mutasi->id);
            
        } catch (\Exception $e) {
            Log::error('Error in createUnverifiedNotification: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}