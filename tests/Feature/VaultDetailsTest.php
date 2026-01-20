<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(
        User::factory()
            ->hasVaults(Vault::factory())
            ->create()
    );
});

it('can add to vault', function () {
    livewire('pages::vault-details.index', ['vault' => Vault::first()])
        ->call('addToVault', Vault::first())
        ->assertHasNoErrors()
        ->assertRedirect(route('my-vault'));

    $this->assertDatabaseCount('vaults', 1);
});

it('can add to wishlist', function () {
    livewire('pages::vault-details.index', ['vault' => Vault::first()])
        ->call('addToWishlist', Vault::first())
        ->assertHasNoErrors()
        ->assertRedirect(route('wishlist'));

    $this->assertDatabaseCount('vaults', 1);
});

it('can delete a record', function () {
    livewire('pages::vault-details.index', ['vault' => Vault::first()])
        ->set('previous_url', '/my-vault')
        ->call('delete', Vault::first())
        ->assertHasNoErrors()
        ->assertRedirect(route('my-vault'));
});

test('component can render', function () {
    livewire('pages::vault-details.index', ['vault' => Vault::first()])
        ->set('previous_url', '/test')
        ->assertHasNoErrors();
});
