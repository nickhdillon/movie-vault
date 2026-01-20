<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
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
    livewire('pages::my-vault.index')
        ->set('search', 'Suits')
        ->assertHasNoErrors();

    expect(Str::contains(URL::current(), '?page'))
        ->toBeFalse();
});

it('can add to wishlist', function () {
    livewire('pages::my-vault.index')
        ->call('addToWishlist', Vault::first())
        ->assertHasNoErrors();

    $this->assertDatabaseCount('vaults', 1);
});

it('can delete a vault record', function () {
    livewire('pages::my-vault.index')
        ->call('delete', Vault::first())
        ->assertHasNoErrors();

    $this->assertDatabaseCount('vaults', 0);
});

it('can select type', function () {
    livewire('pages::my-vault.index')
        ->set('type', 'movie')
        ->assertHasNoErrors();
});

it('can select ratings', function () {
    livewire('pages::my-vault.index')
        ->set('selected_ratings', ['PG'])
        ->assertHasNoErrors();
});

it('can select genres', function () {
    livewire('pages::my-vault.index')
        ->set('selected_genres', ['Comedy', 'Crime'])
        ->assertHasNoErrors();
});

it('can clear filters', function () {
    livewire('pages::my-vault.index')
        ->call('clearFilters')
        ->assertSet('type', '')
        ->assertSet('selected_ratings', [])
        ->assertSet('selected_genres', [])
        ->assertHasNoErrors();
});

it('can set sort direction to desc', function () {
    livewire('pages::my-vault.index')
        ->set('sort_direction', 'desc')
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire('pages::my-vault.index')
        ->assertHasNoErrors();
});
