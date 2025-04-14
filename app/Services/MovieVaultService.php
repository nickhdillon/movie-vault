<?php

declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Number;

class MovieVaultService
{
	public static function getRatings(?User $user = null, ?bool $on_wishlist = false): array
	{
		$ratings = [];

		$user = $user ?: auth()->user();

		$user->vaults()
			->whereOnWishlist($on_wishlist)
			->pluck('rating')
			->each(function (string $rating) use (&$ratings): void {
				if (! in_array($rating, $ratings)) {
					$ratings[] = $rating;
				}
			});

		sort($ratings);

		return $ratings;
	}

	public static function getGenres(?User $user = null, ?bool $on_wishlist = false): array
	{
		$genres = [];

		$user = $user ?: auth()->user();

		$user->vaults()
			->whereOnWishlist($on_wishlist)
			->pluck('genres')
			->each(function (string $genre) use (&$genres): void {
				$genres_array = explode(',', $genre);

				foreach ($genres_array as $genre_key) {
					if (! in_array($genre_key, $genres)) {
						$genres[] = $genre_key;
					}
				}
			});

		sort($genres);

		return $genres;
	}

	public static function formatRuntime(int $runtime): string
	{
		if ($runtime >= 60) {
			$hours = intdiv($runtime, 60);

			$minutes = $runtime % 60;

			return Number::format($hours) . 'h ' . Number::format($minutes) . 'm';
		} else {
			return Number::format($runtime) . 'm';
		}
	}

	public static function formatDate(string $date): string
	{
		return Carbon::parse($date)->format('M d, Y');
	}
}
