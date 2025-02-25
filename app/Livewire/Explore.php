<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Data\VaultData;
use Livewire\Attributes\Lazy;
use GuzzleHttp\Promise\Promise;
use Illuminate\Http\Client\Pool;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;

#[Lazy]
class Explore extends Component
{
    public string $search = '';

    public array $search_results = [];

    public function mount(?string $query = null): void
    {
        if ($query) {
            $this->search = $query;
        }
    }

    protected function extractRating(string $media_type, array $detail_response): string
    {
        if ($media_type === 'movie') {
            $releases = $detail_response['release_dates']['results'] ?? [];

            $us_releases = collect($releases)->firstWhere('iso_3166_1', 'US')['release_dates'] ?? [];

            return collect($us_releases)->pluck('certification')->filter()->first() ?? 'N/A';
        } else {
            $releases = $detail_response['content_ratings']['results'] ?? [];

            return collect($releases)->firstWhere('iso_3166_1', 'US')['rating'] ?? 'N/A';
        }
    }

    #[Computed]
    public function searchResults(): array
    {
        if (strlen($this->search) < 1) {
            return [];
        }

        $results = Http::withToken(config('services.movie-api.token'))
            ->get("https://api.themoviedb.org/3/search/multi?query={$this->search}&include_adult=false&language=en-US")
            ->json()['results'];

        $detail_requests = Http::pool(
            function (Pool $pool) use ($results): void {
                collect($results)->map(function (array $result) use ($pool): Promise {
                    $endpoint = $result['media_type'] === 'movie' ? 'movie' : 'tv';

                    $append_response = $result['media_type'] === 'movie' ? 'release_dates' : 'content_ratings';

                    return $pool->withToken(config('services.movie-api.token'))
                        ->get("https://api.themoviedb.org/3/{$endpoint}/{$result['id']}?append_to_response={$append_response},credits,external_ids");
                })->toArray();
            }
        );

        return collect($results)->map(
            function (array $result, int $index) use ($detail_requests): array {
                $detail_response = $detail_requests[$index]->json();

                $result['rating'] = $this->extractRating($result['media_type'], $detail_response);

                if (isset($detail_response['genres'])) {
                    $result['genres'] = implode(',', collect($detail_response['genres'])->pluck('name')->toArray());
                }

                foreach (['runtime', 'number_of_seasons'] as $key) {
                    if (isset($detail_response[$key])) {
                        $result[$key] = $detail_response[$key];
                    }
                }

                if (isset($detail_response['credits']['cast'])) {
                    $result['actors'] = collect($detail_response['credits']['cast'])
                        ->pluck('name')
                        ->take(3)
                        ->implode(',');
                }

                if (isset($detail_response['external_ids'])) {
                    $result['imdb_id'] = $detail_response['external_ids']['imdb_id'];
                }

                return $result;
            }
        )->keyBy('id')->toArray();
    }

    public function save(array $media, ?string $wishlist = null): void
    {
        $user_vaults = auth()->user()->vaults();

        if ($in_vault = $user_vaults->whereVaultId($media['id'])->exists()) {
            $name = $media['title'] ?? $media['name'];

            $page = $in_vault ? 'vault' : 'wishlist';

            $this->dispatch('show-toast', [
                'status' => 'danger',
                'message' => "{$name} is already in your {$page}"
            ]);
        } else {
            $user_vaults->create(
                VaultData::from([
                    'vault_id' => $media['id'],
                    'imdb_id' => $media['imdb_id'] !== '' ? $media['imdb_id'] : null,
                    'vault_type' => $media['media_type'],
                    'title' => $media['title'] ?? $media['name'],
                    'original_title' => $media['original_title'] ?? $media['original_name'],
                    'overview' => $media['overview'],
                    'backdrop_path' => $media['backdrop_path'] ?? null,
                    'poster_path' => $media['poster_path'] ?? null,
                    'release_date' => $media['release_date'] ?? null,
                    'first_air_date' => $media['first_air_date'] ?? null,
                    'rating' => $media['rating'] ?: 'None',
                    'genres' => $media['genres'] ?: 'None',
                    'runtime' => $media['runtime'] ?? null,
                    'seasons' => $media['number_of_seasons'] ?? null,
                    'actors' => $media['actors'] ?: 'None',
                    'on_wishlist' => $wishlist ? true : false,
                ])->toArray()
            );

            $media = $media['title'] ?? $media['name'];

            $page = $wishlist ? 'wishlist' : 'vault';

            // $this->dispatch('show-toast', [
            //     'status' => 'success',
            //     'message' => "Successfully added {$media} to your {$page}. 
            //         <a href='" . route('movie-vault.details', $user_vaults->latest()->first()->id) . "' class='text-sm font-medium text-indigo-500 duration-200 ease-in-out hover:text-indigo-600 dark:hover:text-indigo-400'>View details &rarr;</a>",
            //     'timeout' => 10000
            // ]);
        }
    }

    public function placeholder(): string
    {
        return <<<'HTML'
            <div class="flex items-center justify-center py-16">
                <x-large-spinner />
            </div>
        HTML;
    }

    public function render(): View
    {
        return view('livewire.explore');
    }
}
