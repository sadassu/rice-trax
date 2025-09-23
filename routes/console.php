<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:mark-attendance-status')
    ->dailyAt('17:05')
    ->timezone('Asia/Manila');

Schedule::command('notifications:cleanup')->daily();
