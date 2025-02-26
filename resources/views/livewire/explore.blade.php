@use('App\Services\MovieVaultService', 'MovieVaultService')

<div class="w-full mx-auto overflow-y-hidden max-w-7xl">
    <div class="flex items-center justify-between">
        <flux:heading size="xl" level="1">
            Explore
        </flux:heading>

        <flux:button variant="primary" icon="arrow-left" size="sm" href="{{ route('my-vault') }}" wire:navigate>
            Back to vault
        </flux:button>
    </div>

    <div class="rounded-xl bg-slate-50 dark:bg-slate-900 p-1 shadow-inner mt-4">
        <div class="mt-4 px-4">
            <flux:input icon="magnifying-glass" placeholder="Search..." clearable
                wire:model.live.debounce.300ms='search' />
        </div>

        <div class="rounded-lg rounded-b-lg bg-white dark:bg-slate-800/50 mt-5 shadow-xs-with-border px-3.5 m-0.5">
            <div class="grid grid-cols-1 gap-4 py-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($this->searchResults as $media)
                    @isset($media['original_language'])
                        @if ($media['original_language'] === 'en')
                            <div class="rounded-lg shadow-xs border dark:border-slate-700 border-slate-200">
                                <a wire:navigate class="w-full">
                                    <img class="h-[300px] w-full rounded-t-lg object-cover"
                                        src="{{ 'https://image.tmdb.org/t/p/w500/' . $media['poster_path'] ?? $media['backdrop_path'] . '?include_adult=false&language=en-US&page=1' }}"
                                        alt="{{ $media['original_title'] ?? $media['original_name'] }}" />
                                </a>

                                <div class="p-3 text-sm dark:bg-slate-800 rounded-b-lg space-y-1">
                                    <h1 class="text-xl font-bold truncate whitespace-nowrap">
                                        {{ $media['original_title'] ?? $media['original_name'] }}
                                    </h1>

                                    <h3>
                                        Release Date:
                                        {{ Carbon\Carbon::parse($media['release_date'] ?? $media['first_air_date'])->format('M d, Y') }}
                                    </h3>

                                    <p class="truncate">
                                        Genres:
                                        {{ Str::replace(',', ', ', $media['genres']) ?: 'None' }}
                                    </p>

                                    <p>
                                        Rating:
                                        {{ $media['rating'] ?? 'None' }}

                                        @if ($media['imdb_id'] !== '' ? $media['imdb_id'] : null)
                                            -

                                            <a class="text-sm font-medium text-indigo-500 duration-200 ease-in-out hover:text-indigo-600 dark:hover:text-indigo-400"
                                                href="https://www.imdb.com/title/{{ $media['imdb_id'] }}/parentalguide"
                                                target="_blank">
                                                <span class="inline-flex items-center">
                                                    View parents guide

                                                    <flux:icon.arrow-up-right class="w-3 h-3 stroke-3 ml-0.5" />
                                                </span>
                                            </a>
                                        @endif
                                    </p>

                                    @isset($media['runtime'])
                                        <p>
                                            Length:
                                            {{ MovieVaultService::formatRuntime($media['runtime']) }}
                                        </p>
                                    @endisset

                                    @isset($media['number_of_seasons'])
                                        <p>
                                            Seasons:
                                            {{ $media['number_of_seasons'] }}
                                        </p>
                                    @endisset

                                    <p class="truncate">
                                        Actors:
                                        {{ Str::replace(',', ', ', $media['actors']) ?: 'None' }}
                                    </p>

                                    <div class="flex items-center justify-between mt-2 space-x-3">
                                        <form wire:submit='save(@json($media))' class="w-full">
                                            <flux:button variant="primary" class="w-full" size="sm" type="submit">
                                                Add to vault
                                            </flux:button>
                                        </form>

                                        <form wire:submit="save(@js($media), 'wishlist')" class="w-full">
                                            <flux:button variant="filled" class="w-full" size="sm" type="submit">
                                                Add to wishlist
                                            </flux:button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endisset
                @empty
                    <div class="col-span-3 mx-auto text-center">
                        <h1 class="text-lg font-medium text-slate-500" wire:loading.remove>
                            Search for movies or TV shows...
                        </h1>

                        <div class="flex text-center justify-center" wire:loading.flex wire:target='search'>
                            <x-large-spinner />
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
