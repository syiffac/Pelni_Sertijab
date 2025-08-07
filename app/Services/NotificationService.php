<?php

namespace App\Services;

use App\Models\User;
use App\Models\Mutasi;
use App\Notifications\DocumentSubmittedNotification;
use App\Notifications\DocumentUnverifiedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Buat notifikasi untuk admin saat PUK submit dokumen
     */
    public static function createSubmitNotification(Mutasi $mutasi)
    {
        error_log("=== NotificationService::createSubmitNotification STARTED for mutasi ID: " . $mutasi->id);
        
        try {
            Log::info('Starting createSubmitNotification for mutasi ID: ' . $mutasi->id);
            
            // PERBAIKAN: User tidak punya field role, gunakan kriteria lain
            // Berdasarkan data yang terlihat, ambil berdasarkan nama atau semua users
            $admins = User::whereIn('username', ['admin', 'supervisor'])
                ->orWhere('nama_admin', 'like', '%Administrator%')
                ->orWhere('nama_admin', 'like', '%Supervisor%')
                ->get();
                
            error_log("=== Found " . $admins->count() . " admin users by username/nama_admin");
            Log::info('Found ' . $admins->count() . ' admin users by username/nama_admin');
            
            // Jika tidak ada admin, ambil semua users (untuk testing)
            if ($admins->isEmpty()) {
                $admins = User::all(); // Kirim ke semua users untuk testing
                error_log("=== No specific admins found, using all users: " . $admins->count());
                Log::info('No specific admins found, using all users: ' . $admins->count());
            }
            
            // Debug: Log admin details
            foreach ($admins as $admin) {
                $adminName = $admin->nama_admin ?? $admin->username ?? 'Unknown';
                $adminUsername = $admin->username ?? 'no username';
                error_log("=== Admin found - ID: " . $admin->id . ", Name: " . $adminName . ", Username: " . $adminUsername);
                Log::info('Admin found - ID: ' . $admin->id . ', Name: ' . $adminName . ', Username: ' . $adminUsername);
            }
            
            // KIRIM NOTIFIKASI MENGGUNAKAN LARAVEL NOTIFICATIONS
            if ($admins->isNotEmpty()) {
                try {
                    error_log("=== About to send notifications to " . $admins->count() . " users");
                    
                    // PERBAIKAN: Kirim satu per satu untuk debugging lebih baik
                    $successCount = 0;
                    foreach ($admins as $admin) {
                        error_log("=== Sending notification to admin ID: " . $admin->id);
                        
                        try {
                            $admin->notify(new DocumentSubmittedNotification($mutasi));
                            $successCount++;
                            error_log("=== Successfully sent notification to admin ID: " . $admin->id);
                            Log::info('Successfully sent notification to admin ID: ' . $admin->id);
                        } catch (\Exception $singleNotifError) {
                            error_log("=== Error sending notification to admin ID " . $admin->id . ": " . $singleNotifError->getMessage());
                            error_log("=== Single notification error trace: " . $singleNotifError->getTraceAsString());
                            Log::error('Error sending notification to admin ID ' . $admin->id . ': ' . $singleNotifError->getMessage());
                        }
                    }
                    
                    error_log("=== Successfully sent " . $successCount . " out of " . $admins->count() . " notifications");
                    Log::info('Successfully sent ' . $successCount . ' out of ' . $admins->count() . ' notifications');
                    
                } catch (\Exception $notifError) {
                    error_log("=== Error in notification process: " . $notifError->getMessage());
                    error_log("=== Notification error stack trace: " . $notifError->getTraceAsString());
                    Log::error('Error in notification process: ' . $notifError->getMessage());
                    Log::error('Notification error stack trace: ' . $notifError->getTraceAsString());
                    throw $notifError;
                }
            } else {
                error_log("=== No users found to send notifications to");
                Log::warning('No users found to send notifications to');
            }
            
            error_log("=== NotificationService::createSubmitNotification COMPLETED for mutasi ID: " . $mutasi->id);
            Log::info('Successfully completed submit notifications for mutasi ID: ' . $mutasi->id);
            
        } catch (\Exception $e) {
            error_log("=== ERROR in createSubmitNotification: " . $e->getMessage());
            error_log("=== Stack trace: " . $e->getTraceAsString());
            Log::error('Error in createSubmitNotification: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            // Don't throw exception to prevent breaking the main process
        }
    }

    /**
     * Buat notifikasi untuk admin tentang dokumen yang belum diverifikasi
     */
    public static function createUnverifiedNotification(Mutasi $mutasi)
    {
        try {
            Log::info('Starting createUnverifiedNotification for mutasi ID: ' . $mutasi->id);
            
            // PERBAIKAN: User tidak punya field role, gunakan kriteria lain
            $admins = User::whereIn('username', ['admin', 'supervisor'])
                ->orWhere('nama_admin', 'like', '%Administrator%')
                ->orWhere('nama_admin', 'like', '%Supervisor%')
                ->get();
                
            Log::info('Found ' . $admins->count() . ' admin users for unverified notification');
            
            // Jika tidak ada admin, ambil semua users
            if ($admins->isEmpty()) {
                $admins = User::all();
                Log::info('No specific admins found, using all users for unverified notification: ' . $admins->count());
            }
            
            // Cek dan kirim notifikasi hanya jika belum ada untuk mutasi ini
            foreach ($admins as $admin) {
                // Cek apakah sudah ada notifikasi unverified untuk mutasi ini
                $existingNotification = \App\Models\Notification::where('user_id', $admin->id)
                    ->where('mutasi_id', $mutasi->id)
                    ->where('type', 'warning')
                    ->where('title', 'Dokumen Belum Diverifikasi')
                    ->exists();
                    
                if (!$existingNotification) {
                    $admin->notify(new DocumentUnverifiedNotification($mutasi));
                    Log::info('Sent unverified notification to admin ID ' . $admin->id);
                } else {
                    Log::info('Unverified notification already exists for admin ID ' . $admin->id . ' and mutasi ID ' . $mutasi->id);
                }
            }
            
            Log::info('Successfully processed unverified notifications for mutasi ID: ' . $mutasi->id);
            
        } catch (\Exception $e) {
            Log::error('Error in createUnverifiedNotification: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Method untuk membuat notifikasi testing
     */
    public static function createTestNotification($userId = null)
    {
        try {
            $user = $userId ? User::find($userId) : (auth()->user() ?? User::find(1));
            
            if (!$user) {
                throw new \Exception('User not found for test notification');
            }
            
            $notification = \App\Models\Notification::create([
                'type' => 'success',
                'icon' => 'check-circle',
                'title' => 'Test Notification',
                'message' => 'Sistem notifikasi Laravel berjalan dengan baik pada ' . now()->format('d M Y H:i:s'),
                'link' => route('dashboard'),
                'read' => false,
                'user_id' => $user->id,
                'mutasi_id' => null
            ]);
            
            Log::info('Created test notification for user ID ' . $user->id);
            
            return $notification;
            
        } catch (\Exception $e) {
            Log::error('Error in createTestNotification: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Method sederhana untuk testing
     */
    public static function testSimpleNotification()
    {
        error_log("=== testSimpleNotification STARTED");
        
        try {
            $user = User::first();
            if (!$user) {
                error_log("=== No users found");
                return false;
            }
            
            error_log("=== User found: " . $user->id);
            
            $notification = \App\Models\Notification::create([
                'type' => 'success',
                'icon' => 'check-circle',
                'title' => 'Simple Test Notification',
                'message' => 'This is a simple test notification at ' . now(),
                'link' => '/dashboard',
                'read' => false,
                'user_id' => $user->id,
                'mutasi_id' => null,
            ]);
            
            error_log("=== Simple notification created with ID: " . $notification->id);
            return $notification;
            
        } catch (\Exception $e) {
            error_log("=== Error in testSimpleNotification: " . $e->getMessage());
            return false;
        }
    }
}