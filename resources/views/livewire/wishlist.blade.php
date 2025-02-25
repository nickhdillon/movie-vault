@use('App\Services\MovieVaultService', 'MovieVaultService')

<div class="w-full mx-auto overflow-y-hidden max-w-7xl">
    <div class="flex flex-col justify-between sm:flex-row sm:items-center">
        <div class="flex flex-row items-center space-x-1.5">
            <flux:heading size="xl" level="1">
                My Wishlist
            </flux:heading>

            <flux:heading size="xl" level="1">
                (Total: {{ $wishlist_records->total() }})
            </flux:heading>
        </div>

        <div class="flex items-center mt-2 space-x-2 sm:mt-0">
            <flux:button variant="primary" icon="film" size="sm" href="{{ route('my-vault') }}" wire:navigate
                class="w-full sm:w-auto">
                Vault
            </flux:button>

            <flux:button variant="primary" icon="plus" size="sm" href="{{ route('explore') }}" wire:navigate
                class="w-full sm:w-auto">
                Add to vault
            </flux:button>
        </div>
    </div>

    <div class="rounded-lg border dark:bg-slate-900 border-slate-200 dark:border-slate-700 mt-4 bg-slate-50">
        <div class="flex items-center mt-4 px-4 space-x-2">
            <flux:input icon="magnifying-glass" placeholder="Search..." clearable
                wire:model.live.debounce.300ms='search' />

            <x-filters :$ratings :$genres />
        </div>

        <div class="rounded-t-lg rounded-b-lg bg-white mt-4 border-t dark:bg-slate-800/50 px-4 dark:border-slate-700 border-slate-200"
            wire:loading.remove wire:target='search,type,selected_ratings,selected_genres,sort_direction'>
            <div class="grid grid-cols-1 gap-4 pt-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($wishlist_records as $vault)
                    <div class="rounded-lg shadow-xs border dark:border-slate-700 border-slate-200"
                        wire:key='{{ $vault->id }}'>
                        <a href="{{ route('details', $vault->id) }}" wire:navigate class="w-full">
                            <img class="h-[300px] w-full rounded-t-lg object-cover"
                                src="{{ 'https://image.tmdb.org/t/p/w500/' . $vault->poster_path ?? $vault->backdrop_path . '?include_adult=false&language=en-US&page=1' }}"
                                alt="{{ $vault->original_title }}" />
                        </a>

                        <div class="p-3 text-sm dark:bg-slate-800 rounded-b-lg space-y-1">
                            <h1 class="text-lg font-semibold truncate whitespace-nowrap">
                                {{ $vault->original_title }}
                            </h1>

                            <h3>
                                {{ $vault->release_date ? 'Release Date: ' : 'First Air Date: ' }}

                                {{ MovieVaultService::formatDate($vault->release_date ?? $vault->first_air_date) }}
                            </h3>

                            <p class="truncate">
                                Genres: {{ Str::replace(',', ', ', $vault->genres) }}
                            </p>

                            <p>
                                Rating: {{ $vault->rating }}

                                @isset($vault->imdb_id)
                                    -

                                    <a class="text-sm font-medium text-indigo-500 duration-200 ease-in-out hover:text-indigo-600 dark:hover:text-indigo-400"
                                        href="https://www.imdb.com/title/{{ $vault->imdb_id }}/parentalguide"
                                        target="_blank">
                                        <span class="inline-flex items-center">
                                            View parents guide

                                            <flux:icon.arrow-up-right class="w-3 h-3 stroke-3 ml-0.5" />
                                        </span>
                                    </a>
                                @endisset
                            </p>

                            @isset($vault->runtime)
                                <p>
                                    Length:

                                    {{ MovieVaultService::formatRuntime($vault->runtime) }}
                                </p>
                            @endisset

                            @isset($vault->seasons)
                                <p>
                                    Seasons:

                                    {{ $vault->seasons }}
                                </p>
                            @endisset

                            <p class="truncate">
                                Actors:

                                {{ Str::replace(',', ', ', $vault->actors) ?: 'No actors found' }}
                            </p>

                            <div class="flex items-center justify-between -mb-2 w-full">
                                <a class="text-sm font-medium text-indigo-500 duration-200 ease-in-out hover:text-indigo-600 dark:hover:text-indigo-400"
                                    href="{{ route('details', $vault->id) }}" wire:navigate>
                                    View all details &rarr;
                                </a>

                                <div class="flex items-center !-space-x-1 -mr-2">
                                    <flux:modal.trigger name="{{ $vault->id }}-vault">
                                        <flux:button variant="subtle" class="w-3! h-8!">
                                            <flux:icon.plus class="w-5! h-5! text-slate-600! dark:text-slate-200!" />
                                        </flux:button>
                                    </flux:modal.trigger>

                                    <flux:modal name="{{ $vault->id }}-vault" class="w-90 sm:w-120!"
                                        x-on:close-modal.window="$flux.modal('{{ $vault->id }}-vault').close()">
                                        <flux:heading size="lg">
                                            Add to vault
                                        </flux:heading>

                                        <flux:subheading>
                                            Are you sure you want to add

                                            <span class="font-semibold text-indigo-500">
                                                '{{ $vault->title }}'
                                            </span>

                                            to your vault?
                                        </flux:subheading>

                                        <div class="flex mt-6 -mb-1">
                                            <flux:spacer />

                                            <div class="space-x-1 flex items-center">
                                                <flux:modal.close>
                                                    <flux:button size="sm" variant="ghost">
                                                        Cancel
                                                    </flux:button>
                                                </flux:modal.close>

                                                <form wire:submit="addToVault({{ $vault->id }})">
                                                    <flux:button size="sm" type="submit" variant="primary">
                                                        Confirm
                                                    </flux:button>
                                                </form>
                                            </div>
                                        </div>
                                    </flux:modal>

                                    <flux:modal.trigger name="{{ $vault->id }}-delete">
                                        <flux:button variant="subtle" class="w-3! h-8!">
                                            <flux:icon.trash class="text-red-500 w-5! h-5!" />
                                        </flux:button>
                                    </flux:modal.trigger>

                                    <flux:modal name="{{ $vault->id }}-delete" class="w-90 sm:w-120!"
                                        x-on:close-modal.window="$flux.modal('{{ $vault->id }}-delete').close()">
                                        <flux:heading size="lg">
                                            Remove from wishlist
                                        </flux:heading>

                                        <flux:subheading>
                                            Are you sure you want to remove

                                            <span class="font-semibold text-red-500">
                                                '{{ $vault->title }}'
                                            </span>

                                            from your wishlist?
                                        </flux:subheading>

                                        <div class="flex mt-6 -mb-1">
                                            <flux:spacer />

                                            <div class="space-x-1 flex items-center">
                                                <flux:modal.close>
                                                    <flux:button size="sm" variant="ghost">
                                                        Cancel
                                                    </flux:button>
                                                </flux:modal.close>

                                                <form wire:submit="delete({{ $vault->id }})">
                                                    <flux:button size="sm" type="submit" variant="danger">
                                                        Delete
                                                    </flux:button>
                                                </form>
                                            </div>
                                        </div>
                                    </flux:modal>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 mx-auto text-center">
                        <h1 class="text-lg font-semibold text-slate-500">
                            Search movies or TV shows from the

                            <a class="-mr-1 font-medium text-indigo-500 duration-200 ease-in-out hover:text-indigo-600 dark:hover:text-indigo-400"
                                href="{{ route('explore', $search ?: null) }}" wire:navigate>
                                Explore page
                            </a>.
                        </h1>
                    </div>
                @endforelse
            </div>

            <div class="pt-4 @if (count($wishlist_records) > 9) pb-4 @endif">
                {{ $wishlist_records->links() }}
            </div>
        </div>

        <div class="flex justify-center my-6" wire:loading.flex
            wire:target='search,type,selected_ratings,selected_genres,sort_direction'>
            <x-large-spinner />
        </div>
    </div>
</div>
