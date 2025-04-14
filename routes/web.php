<?php

use Livewire\Volt\Volt;
use App\Livewire\MyVault;
use App\Livewire\Explore;
use App\Livewire\Wishlist;
use App\Livewire\VaultDetails;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', MyVault::class)->name('my-vault');

    Route::get('explore/{query?}', Explore::class)->name('explore');

    Route::get('{vault:slug}/details', VaultDetails::class)->name('details');

    Route::get('wishlist', Wishlist::class)->name('wishlist');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
});

Route::get('{user:slug}/vault', MyVault::class)->name('view-user-vault');

Route::get('{user:slug}/wishlist', Wishlist::class)->name('view-user-wishlist');

require __DIR__ . '/auth.php';
