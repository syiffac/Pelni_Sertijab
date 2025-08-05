<?php

namespace App\Console\Commands;

use App\Models\Mutasi;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendUnverifiedNotifications extends Command
{
    protected $signature = 'notifications:unverified';
    protected $description = 'Send notifications for unverified documents';

    public function handle()
    {
        $this->info('Checking for unverified documents...');
        
        $unverifiedMutasis = Mutasi::where('submitted_by_puk', true)
            ->where('verified_by_admin', false)
            ->where('submitted_at', '<=', Carbon::now()->subDays(1))
            ->get();
            
        $count = 0;
        
        foreach ($unverifiedMutasis as $mutasi) {
            NotificationService::createUnverifiedNotification($mutasi);
            $count++;
        }
        
        $this->info("Sent {$count} notifications for unverified documents.");
        
        return Command::SUCCESS;
    }
}
