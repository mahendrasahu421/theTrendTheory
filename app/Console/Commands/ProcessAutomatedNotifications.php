<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class ProcessAutomatedNotifications extends Command
{
    protected $signature = 'notifications:process-automation';
    protected $description = 'Process automated notifications for abandoned carts, inactive users, and offers';

    public function handle(NotificationService $service)
    {
        $this->info('Starting automated notification processing...');

        // 1. Process Abandoned Carts
        $cartCount = $service->processAbandonedCartReminders();
        $this->info("Abandoned cart reminders dispatched: {$cartCount}");

        // 2. Process Inactive Users
        $inactiveCount = $service->processInactiveUserReminders();
        $this->info("Inactive user re-engagement notifications dispatched: {$inactiveCount}");

        $this->info('Automated notification processing complete.');
        return Command::SUCCESS;
    }
}
