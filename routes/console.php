<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Schedule your commands here using `php artisan schedule:run`
 * We'll call them from a cron job
 */
Artisan::command('schedule:run', function () {
    // This manually runs your commands
    Artisan::call('app:mark-attendance-status');
    Artisan::call('notifications:cleanup');
})->describe('Run all scheduled tasks');
