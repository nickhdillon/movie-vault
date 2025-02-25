<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use App\Livewire\Wishlist;
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
    livewire(Wishlist::class)
        ->set('search', 'Suits')
        ->assertHasNoErrors();

    expect(Str::contains(URL::current(), '?page'))
        ->toBeFalse();
});

it('can add a record to vault', function () {
    livewire(Wishlist::class)
        ->call('addToVault', Vault::first())
        ->assertHasNoErrors()
        ->assertNoRedirect();
});

it('can delete a wishlist record', function () {
    livewire(Wishlist::class)
        ->call('delete', Vault::first())
        ->assertHasNoErrors();

    $this->assertDatabaseCount('vaults', 0);
});

it('can select type', function () {
    livewire(Wishlist::class)
        ->set('type', 'movie')
        ->assertHasNoErrors();
});

it('can select ratings', function () {
    livewire(Wishlist::class)
        ->set('selected_ratings', ['PG'])
        ->assertHasNoErrors();
});

it('can select genres', function () {
    livewire(Wishlist::class)
        ->set('selected_genres', ['Comedy', 'Crime'])
        ->assertHasNoErrors();
});

it('can clear filters', function () {
    livewire(Wishlist::class)
        ->call('clearFilters')
        ->assertSet('type', '')
        ->assertSet('selected_ratings', [])
        ->assertSet('selected_genres', [])
        ->assertHasNoErrors();
});

it('can set sort direction to desc', function () {
    livewire(Wishlist::class)
        ->set('sort_direction', 'desc')
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Wishlist::class)
        ->assertHasNoErrors();
});
