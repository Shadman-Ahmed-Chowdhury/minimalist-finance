<?php
use App\Livewire\Expenses\Index;
use Illuminate\Support\Facades\Route;

Route::view('', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('expenses', 'expenses')
    ->middleware(['auth'])
    ->name('expenses');

require __DIR__.'/auth.php';
