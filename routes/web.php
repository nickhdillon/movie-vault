<?php

use Livewire\Volt\Volt;
use App\Livewire\MyVault;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', MyVault::class)->name('my-vault');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
});

require __DIR__ . '/auth.php';
