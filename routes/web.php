<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::livewire('/', 'pages::my-vault')->name('my-vault');

    Route::livewire('explore/{query?}', 'pages::explore')->name('explore');

    Route::livewire('{vault:slug}/details', 'pages::vault-details')->name('details');

    Route::livewire('wishlist', 'pages::wishlist')->name('wishlist');

    Route::livewire('friends', 'pages::friends')->name('friends');

    Route::redirect('settings', 'pages::settings/profile');

    Route::livewire('settings/profile', 'pages::settings.profile')->name('settings.profile');
    Route::livewire('settings/password', 'pages::settings.password')->name('settings.password');
});

Route::livewire('{user:slug}/vault', 'pages::my-vault')->name('view-user-vault');

Route::livewire('{user:slug}/wishlist', 'pages::wishlist')->name('view-user-wishlist');

Route::livewire('{user:slug}/{vault:slug}/details', 'pages::vault-details')->name('view-user-details');

require __DIR__ . '/auth.php';
