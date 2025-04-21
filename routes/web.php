<?php

use App\Livewire\Expenses;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::middleware(['auth'])->group(function () {
    Volt::route('/expenses', 'expenses.table')->name('expenses');

    Volt::route('/income', 'pages.income')->name('income');
    Volt::route('/transfer', 'pages.transfer')->name('transfer');
    Volt::route('/accounts', 'pages.account')->name('account');
    Volt::route('/categories', 'pages.category')->name('category');
});

require __DIR__ . '/auth.php';
