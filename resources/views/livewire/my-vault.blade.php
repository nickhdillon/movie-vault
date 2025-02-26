@use('App\Services\MovieVaultService', 'MovieVaultService')

<div class="w-full mx-auto overflow-y-hidden max-w-7xl">
    <div class="flex flex-col justify-between sm:flex-row sm:items-center">
        <div class="flex flex-row items-center space-x-1.5">
            <flux:heading size="xl" level="1">
                My Vault
            </flux:heading>

            <flux:heading size="xl" level="1">
                (Total: {{ $vault_records->total() }})
            </flux:heading>
        </div>

        <div class="flex items-center mt-2 space-x-2 sm:mt-0">
            <flux:button href="{{ route('wishlist') }}" variant="primary" size="sm" wire:navigate
                class="w-full sm:w-auto">
                <flux:icon icon="heart" variant="outline" class="w-4 h-4" />

                Wishlist
            </flux:button>

            <flux:button variant="primary" size="sm" icon="plus" href="{{ route('explore') }}" wire:navigate
                class="w-full sm:w-auto">
                Add to vault
            </flux:button>
        </div>
    </div>

    <div class="rounded-xl bg-slate-50 dark:bg-slate-900 p-1 shadow-inner mt-4">
        <div class="flex items-center mt-4 px-4 space-x-2">
            <flux:input icon="magnifying-glass" placeholder="Search..." clearable
                wire:model.live.debounce.300ms='search' />

            <x-filters :$ratings :$genres />
        </div>

        <div class="rounded-lg rounded-b-lg bg-white dark:bg-slate-800/50 mt-5 shadow-xs-with-border px-3.5 m-0.5"
            wire:loading.remove wire:target='search,type,selected_ratings,selected_genres,sort_direction'>
            <div class="grid grid-cols-1 gap-4 pt-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($vault_records as $vault)
                    <div class="rounded-lg shadow-xs border dark:border-slate-700 border-slate-200"
                        wire:key='{{ $vault->id }}'>
                        <a href="{{ route('details', $vault->id) }}" wire:navigate class="w-full">
                            <img class="h-[300px] w-full rounded-t-lg object-cover"
                                src="{{ 'https://image.tmdb.org/t/p/w500/' . $vault->poster_path ?? $vault->backdrop_path . '?include_adult=false&language=en-US&page=1' }}"
                                alt="{{ $vault->original_title }}" />
                        </a>

                        <div class="p-3 text-sm bg-slate-50/40 dark:bg-slate-800 rounded-b-lg space-y-1">
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

                            <div class="flex items-center justify-between w-full">
                                <a class="text-sm font-medium text-indigo-500 duration-200 ease-in-out hover:text-indigo-600 dark:hover:text-indigo-400"
                                    href="{{ route('details', $vault->id) }}" wire:navigate>
                                    View all details &rarr;
                                </a>

                                <div class="flex items-center space-x-0.5 -mr-1.5">
                                    <flux:modal.trigger name="{{ $vault->id }}-wishlist">
                                        <flux:button variant="subtle" icon="plus"
                                            class="h-6! w-6! text-slate-600! dark:text-slate-200! rounded-md!" />
                                    </flux:modal.trigger>

                                    <flux:modal name="{{ $vault->id }}-wishlist" class="w-90 sm:w-120!"
                                        x-on:close-modal.window="$flux.modal('{{ $vault->id }}-wishlist').close()">
                                        <flux:heading size="lg">
                                            Add to wishlist
                                        </flux:heading>

                                        <flux:subheading>
                                            Are you sure you want to add

                                            <span class="font-semibold text-indigo-500">
                                                '{{ $vault->title }}'
                                            </span>

                                            to your wishlist?
                                        </flux:subheading>

                                        <div class="flex mt-6 -mb-1">
                                            <flux:spacer />

                                            <div class="space-x-1 flex items-center">
                                                <flux:modal.close>
                                                    <flux:button size="sm" variant="ghost">
                                                        Cancel
                                                    </flux:button>
                                                </flux:modal.close>

                                                <form wire:submit="addToWishlist({{ $vault->id }})">
                                                    <flux:button size="sm" type="submit" variant="primary">
                                                        Confirm
                                                    </flux:button>
                                                </form>
                                            </div>
                                        </div>
                                    </flux:modal>

                                    <flux:modal.trigger name="{{ $vault->id }}-delete">
                                        <flux:button variant="subtle" icon="trash"
                                            class="h-6! w-6! text-red-500! rounded-md!" />
                                    </flux:modal.trigger>

                                    <flux:modal name="{{ $vault->id }}-delete" class="w-90 sm:w-120!"
                                        x-on:close-modal.window="$flux.modal('{{ $vault->id }}-delete').close()">
                                        <flux:heading size="lg">
                                            Remove from vault
                                        </flux:heading>

                                        <flux:subheading>
                                            Are you sure you want to remove

                                            <span class="font-semibold text-red-500">
                                                '{{ $vault->title }}'
                                            </span>

                                            from your vault?
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
                        <h1 class="text-lg font-medium text-slate-500">
                            Search movies or TV shows from the

                            <a class="-mr-1 font-medium text-indigo-500 duration-200 ease-in-out hover:text-indigo-600 dark:hover:text-indigo-400"
                                href="{{ route('explore', $search ?: null) }}" wire:navigate>
                                Explore page
                            </a>.
                        </h1>
                    </div>
                @endforelse
            </div>

            <div class="pt-4 @if ($vault_records->total() > 9) pb-4 @endif">
                {{ $vault_records->links() }}
            </div>
        </div>

        <div class="flex justify-center mt-6 mb-4" wire:loading.flex
            wire:target='search,type,selected_ratings,selected_genres,sort_direction'>
            <x-large-spinner />
        </div>
    </div>
</div>
