<?php

declare(strict_types=1);

use App\Models\User;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('user can delete their account', function () {
    $response = livewire('delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertNull(User::first());
    $this->assertFalse(auth()->check());
});

test('correct password must be provided to delete account', function () {
    $response = livewire('delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    $this->assertNotNull(User::first()->fresh());
});

test('component can render', function () {
    livewire('delete-user-form')
        ->assertSeeLivewire('delete-user-form');
});
