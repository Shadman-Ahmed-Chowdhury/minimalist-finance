<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('loans:send-reminders', function () {
    // Command logic remains in app/Console/Commands/SendLoanReminders.php
})->purpose('Send loan reminder emails two days before due date');

Artisan::command('reports:send-monthly', function () {
    // Command logic in app/Console/Commands/SendMonthlyReports.php
})->purpose('Generate and email last month\'s reports for loans, expenses, and income');

\Illuminate\Support\Facades\Schedule::command('loans:send-reminders')
    ->dailyAt('10:00')
    ->withoutOverlapping()
    ->onOneServer();

\Illuminate\Support\Facades\Schedule::command('reports:send-monthly')
->monthlyOn(1, '09:00')
->withoutOverlapping()
->onOneServer();
