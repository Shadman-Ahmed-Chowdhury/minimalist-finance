<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('loans:send-reminders', function () {
    // Command logic remains in app/Console/Commands/SendLoanReminders.php
})->purpose('Send loan reminder emails two days before due date');

\Illuminate\Support\Facades\Schedule::command('loans:send-reminders')
    ->dailyAt('10:00')
    ->withoutOverlapping()
    ->onOneServer();
