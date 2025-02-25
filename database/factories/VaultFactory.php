<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vault>
 */
class VaultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'vault_id' => 1234,
            'imdb_id' => 'tt0108037',
            'vault_type' => 'movie',
            'title' => 'Movie Title',
            'original_title' => 'Movie Title',
            'overview' => 'Movie Overview',
            'backdrop_path' => '',
            'poster_path' => '/' . Str::uuid(),
            'release_date' => '2024-07-27',
            'first_air_date' => '',
            'rating' => 'PG',
            'genres' => 'Comedy,Crime,Family',
            'runtime' => Arr::random([45, 60]),
            'seasons' => 9,
            'actors' => 'Nick,Anna,Howie',
            'on_wishlist' => 0,
        ];
    }
}
