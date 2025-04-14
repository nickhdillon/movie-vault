<?php

declare(strict_types=1);

use App\Models\Vault;
use Illuminate\Support\Str;

it('generates slugs for existing vaults', function () {
    $vault = Vault::factory()->create(['slug' => null]);

    $this->artisan('generate-vault-slugs')->assertExitCode(0);

    $vault->refresh();

    expect($vault->slug)->toBe(Str::slug($vault->title));
});
