<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use App\Livewire\MyVault;
use Illuminate\Support\Str;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\URL;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(
        User::factory()
            ->hasVaults(Vault::factory())
            ->create()
    );
});

it('can update search', function () {
    livewire(MyVault::class)
        ->set('search', 'Suits')
        ->assertHasNoErrors();

    expect(Str::contains(URL::current(), '?page'))
        ->toBeFalse();
});

it('can add to wishlist', function () {
    livewire(MyVault::class)
        ->call('addToWishlist', Vault::first())
        ->assertHasNoErrors();

    $this->assertDatabaseCount('vaults', 1);
});

it('can delete a vault record', function () {
    livewire(MyVault::class)
        ->call('delete', Vault::first())
        ->assertHasNoErrors();

    $this->assertDatabaseCount('vaults', 0);
});

it('can select type', function () {
    livewire(MyVault::class)
        ->set('type', 'movie')
        ->assertHasNoErrors();
});

it('can select ratings', function () {
    livewire(MyVault::class)
        ->set('selected_ratings', ['PG'])
        ->assertHasNoErrors();
});

it('can select genres', function () {
    livewire(MyVault::class)
        ->set('selected_genres', ['Comedy', 'Crime'])
        ->assertHasNoErrors();
});

it('can clear filters', function () {
    livewire(MyVault::class)
        ->call('clearFilters')
        ->assertSet('type', '')
        ->assertSet('selected_ratings', [])
        ->assertSet('selected_genres', [])
        ->assertHasNoErrors();
});

it('can set sort direction to desc', function () {
    livewire(MyVault::class)
        ->set('sort_direction', 'desc')
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(MyVault::class)
        ->assertHasNoErrors();
});
