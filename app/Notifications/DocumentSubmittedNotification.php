<?php

namespace App\Notifications;

use App\Models\Mutasi;
use App\Channels\CustomDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route; // TAMBAHAN: Import Route facade

class DocumentSubmittedNotification extends Notification
{
    use Queueable;

    protected $mutasi;

    /**
     * Create a new notification instance.
     */
    public function __construct(Mutasi $mutasi)
    {
        $this->mutasi = $mutasi;
        Log::info('DocumentSubmittedNotification created for mutasi ID: ' . $mutasi->id);
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        Log::info('DocumentSubmittedNotification via() called for user ID: ' . $notifiable->id);
        return [CustomDatabaseChannel::class]; // Gunakan custom channel
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        // PERBAIKAN: Gunakan route yang ada atau URL manual
        $link = '#';
        try {
            // Coba gunakan route monitoring jika ada
            if (Route::has('monitoring.index')) {
                $link = route('monitoring.index') . '?mutasi_id=' . $this->mutasi->id;
            } elseif (Route::has('mutasi.index')) {
                $link = route('mutasi.index') . '?id=' . $this->mutasi->id;
            } else {
                // Fallback ke dashboard
                $link = route('dashboard');
            }
        } catch (\Exception $e) {
            Log::warning('Error generating notification link: ' . $e->getMessage());
            $link = '/dashboard'; // Fallback manual
        }
        
        $data = [
            'type' => 'info',
            'icon' => 'file-earmark-check',
            'title' => 'Dokumen Baru Disubmit',
            'message' => "PUK telah mensubmit dokumen untuk Mutasi MUT-" . str_pad($this->mutasi->id, 4, '0', STR_PAD_LEFT) . " - " . $this->mutasi->nama_lengkap_naik,
            'link' => $link,
            'mutasi_id' => $this->mutasi->id,
            'mutasi_code' => 'MUT-' . str_pad($this->mutasi->id, 4, '0', STR_PAD_LEFT),
            'abk_name' => $this->mutasi->nama_lengkap_naik
        ];
        
        Log::info('DocumentSubmittedNotification toArray() called', $data);
        return $data;
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        Log::info('DocumentSubmittedNotification toDatabase() called for user ID: ' . $notifiable->id);
        return $this->toArray($notifiable);
    }

    /**
     * Method untuk mendapatkan data - digunakan oleh CustomDatabaseChannel
     */
    public function getData($notifiable)
    {
        Log::info('DocumentSubmittedNotification getData() called for user ID: ' . $notifiable->id);
        return $this->toArray($notifiable);
    }
}
