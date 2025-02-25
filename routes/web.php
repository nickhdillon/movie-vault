<?php

use Livewire\Volt\Volt;
use App\Livewire\MyVault;
use App\Livewire\Explore;
use App\Livewire\VaultDetails;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', MyVault::class)->name('my-vault');

    Route::get('explore/{query?}', Explore::class)->name('explore');

    Route::get('{vault}/details', VaultDetails::class)->name('details');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
});

require __DIR__ . '/auth.php';
