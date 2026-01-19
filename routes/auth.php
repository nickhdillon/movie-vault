<?php

declare(strict_types=1);

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::livewire('login', 'auth.login')
        ->name('login');

    Route::livewire('register', 'auth.register')
        ->name('register');

    Route::livewire('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Route::livewire('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');
});

Route::post('logout', Logout::class)
    ->name('logout');
