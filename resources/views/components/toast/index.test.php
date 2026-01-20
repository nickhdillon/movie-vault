<?php

declare(strict_types=1);

use App\Models\User;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can show toast from session', function () {
    session()->flash('toast', [
        'status' => 'success',
        'message' => 'Test message'
    ]);

    livewire('toast')
        ->assertSet('toasts', [
            [
                'status' => 'success',
                'message' => 'Test message',
                'timeout' => 5000,
            ]
        ])
        ->assertHasNoErrors();
});

it('can show toast on event', function () {
    livewire('toast')
        ->call('showToast', [
            'status' => 'success',
            'message' => 'Test message'
        ])
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire('toast')
        ->assertSeeLivewire('toast');
});
