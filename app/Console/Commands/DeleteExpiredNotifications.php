<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;

class DeleteExpiredNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete notifications older than 7 days or past their expiration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Notification::where('created_at', '<', now()->subDays(7))
            ->orWhere('expires_at', '<', now())
            ->delete();

        $this->info('Expired notifications cleaned up.');
    }
}
