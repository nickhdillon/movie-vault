<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Str;

it('generates slugs for existing users', function () {
    $user = User::factory()->create(['slug' => null]);

    $this->artisan('generate-user-slugs')->assertExitCode(0);

    $user->refresh();

    expect($user->slug)->toBe(Str::slug($user->name));
});
