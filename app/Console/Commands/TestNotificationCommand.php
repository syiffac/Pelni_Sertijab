<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class TestNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:test {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test notification system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id') ?? 1;

        try {
            $notification = NotificationService::createTestNotification($userId);
            $this->info("Test notification created successfully for user ID: {$userId}");
            $this->info("Notification ID: {$notification->id}");
        } catch (\Exception $e) {
            $this->error("Error creating test notification: " . $e->getMessage());
        }
    }
}
