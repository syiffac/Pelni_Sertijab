<?php
// Buat file: app/Channels/CustomDatabaseChannel.php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CustomDatabaseChannel
{
    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification)
    {
        Log::info('CustomDatabaseChannel send() called for user ID: ' . $notifiable->getKey());
        
        try {
            $data = $this->getData($notifiable, $notification);
            Log::info('CustomDatabaseChannel got data:', $data);
            
            // Insert ke tabel notifications custom
            $createdNotification = \App\Models\Notification::create([
                'type' => $data['type'] ?? 'info',
                'icon' => $data['icon'] ?? 'bell',
                'title' => $data['title'] ?? 'Notification',
                'message' => $data['message'] ?? '',
                'link' => $data['link'] ?? null,
                'read' => false,
                'user_id' => $notifiable->getKey(),
                'mutasi_id' => $data['mutasi_id'] ?? null,
            ]);
            
            Log::info('CustomDatabaseChannel created notification ID: ' . $createdNotification->id);
            return $createdNotification;
            
        } catch (\Exception $e) {
            Log::error('Error in CustomDatabaseChannel send(): ' . $e->getMessage());
            Log::error('CustomDatabaseChannel error stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Get the data for the notification.
     */
    protected function getData($notifiable, Notification $notification)
    {
        Log::info('CustomDatabaseChannel getData() called');
        
        if (method_exists($notification, 'toDatabase')) {
            Log::info('Using toDatabase() method');
            return $notification->toDatabase($notifiable);
        }

        if (method_exists($notification, 'toArray')) {
            Log::info('Using toArray() method');
            return $notification->toArray($notifiable);
        }

        if (method_exists($notification, 'getData')) {
            Log::info('Using getData() method');
            return $notification->getData($notifiable);
        }

        throw new \RuntimeException('Notification is missing toDatabase / toArray / getData method.');
    }
}