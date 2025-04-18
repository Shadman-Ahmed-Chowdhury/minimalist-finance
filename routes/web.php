<?php
use App\Livewire\Expenses;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::middleware(['auth'])->group(function () {
    Route::get('/expenses', Expenses::class)->name('expenses');
});

require __DIR__.'/auth.php';
