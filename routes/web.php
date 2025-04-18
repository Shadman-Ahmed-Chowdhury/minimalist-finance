<?php
use App\Livewire\Expenses\Index;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::middleware(['auth'])->group(function () {
    Route::get('/expenses', Index::class)->name('expenses.index');

    Volt::route('/income', 'pages.income')->name('income');
});

require __DIR__.'/auth.php';
