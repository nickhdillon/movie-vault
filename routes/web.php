<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::livewire('/', 'pages::my-vault.index')->name('my-vault');

    Route::livewire('explore/{query?}', 'pages::explore.index')->name('explore')->lazy();

    Route::livewire('{vault:slug}/details', 'pages::vault-details.index')->name('details');

    Route::livewire('wishlist', 'pages::wishlist.index')->name('wishlist');

    Route::redirect('settings', 'pages::settings/profile');

    Route::livewire('settings/profile', 'pages::settings.profile')->name('settings.profile');
    Route::livewire('settings/password', 'pages::settings.password')->name('settings.password');
});

Route::livewire('{user:slug}/vault', 'pages::my-vault.index')->name('view-user-vault');

Route::livewire('{user:slug}/wishlist', 'pages::wishlist.index')->name('view-user-wishlist');

require __DIR__ . '/auth.php';
