@use('App\Services\MovieVaultService', 'MovieVaultService')

<div x-data="{
    copyShareableUrl() {
        navigator.clipboard.writeText('{{ route('view-user-vault', auth()->user()) }}');
        $dispatch('url-copied');
    }
}" class="w-full mx-auto overflow-y-hidden max-w-7xl">
    <div class="flex flex-col justify-between sm:flex-row sm:items-center">
        <div class="flex flex-row items-center space-x-1.5">
            <flux:heading size="xl" level="1">
                {{ $user ? "{$user->name}'s Vault" : 'My Vault' }}
            </flux:heading>

            <flux:heading size="xl" level="1">
                (Total: {{ $vault_records->total() }})
            </flux:heading>

            @if (!$this->user) 
                <div class="flex items-center">
                    <flux:button icon="share" variant="ghost" size="sm" class="hover:bg-slate-100! -ml-1 dark:hover:bg-slate-700!" x-on:click="copyShareableUrl" />

                    <x-action-message class="ml-2 text-slate-800! dark:text-slate-200!" on="url-copied">
                        {{ __('URL copied!') }}
                    </x-action-message>
                </div>
            @endif
        </div>

        <div class="flex items-center mt-2 space-x-2 sm:mt-0">
            <flux:button href="{{ $user ? route('view-user-wishlist', $user) : route('wishlist') }}" variant="primary" size="sm" wire:navigate
                class="w-full sm:w-auto">
                <flux:icon icon="heart" variant="outline" class="w-4 h-4" />

                Wishlist
            </flux:button>

            @if (!$user) 
                <flux:button href="{{ route('explore') }}" variant="primary" size="sm" wire:navigate
                    class="w-full sm:w-auto">
                    <flux:icon icon="globe-alt" variant="outline" class="w-4 h-4" />
    
                    Explore
                </flux:button>
            @endif
        </div>
    </div>

    <div class="rounded-[12px] bg-slate-50 dark:bg-slate-900 p-1 shadow-inner mt-4">
        <div class="flex items-center mt-3 px-3 space-x-2">
            <flux:input icon="magnifying-glass" placeholder="Search..." clearable
                wire:model.live.debounce.300ms='search' />

            <x-filters :$ratings :$genres />
        </div>

        <div wire:loading.remove wire:target='search,type,selected_ratings,selected_genres,sort_direction'
            class="px-3">
            <div class="grid grid-cols-1 gap-3 pt-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($vault_records as $vault)
                    <div class="rounded-[12px] bg-white dark:bg-slate-800 shadow-xs px-[5px] pt-[5px] border dark:border-slate-700 border-slate-200"
                        wire:key='{{ $vault->id }}'>
                        @if (!$user)
                            <a href="{{ route('details', $vault) }}" wire:navigate class="w-full">
                                <img class="h-[300px] w-full rounded-[8px] object-cover border dark:border-slate-700 border-slate-200"
                                    src="{{ 'https://image.tmdb.org/t/p/w500/' . $vault->poster_path ?? $vault->backdrop_path . '?include_adult=false&language=en-US&page=1' }}"
                                    alt="{{ $vault->title ?? $vault->original_title }}" />
                            </a>
                        @else
                            <div class="w-full">
                                <img class="h-[300px] w-full rounded-[8px] object-cover border dark:border-slate-700 border-slate-200"
                                    src="{{ 'https://image.tmdb.org/t/p/w500/' . $vault->poster_path ?? $vault->backdrop_path . '?include_adult=false&language=en-US&page=1' }}"
                                    alt="{{ $vault->title ?? $vault->original_title }}" />
                            </div>
                        @endif

                        <div class="p-3 text-sm bg-white dark:bg-slate-800 rounded-b-lg space-y-1">
                            <h1 class="text-lg font-semibold truncate whitespace-nowrap">
                                {{ $vault->title ?? $vault->original_title }}
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

                            @if(!$user)
                                <div class="flex items-center justify-between w-full">
                                    <a class="text-sm font-medium text-indigo-500 duration-200 ease-in-out hover:text-indigo-600 dark:hover:text-indigo-400"
                                        href="{{ route('details', $vault) }}" wire:navigate>
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
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 mx-auto text-center">
                        <h1 class="text-lg font-medium text-slate-500">
                            @if (!$user) 
                                Search movies or TV shows from the

                                <a class="-mr-1 font-medium text-indigo-500 duration-200 ease-in-out hover:text-indigo-600 dark:hover:text-indigo-400"
                                    href="{{ route('explore', $search ?: null) }}" wire:navigate>
                                    Explore page
                                </a>.
                            @else
                                {{ "{$user->name}'s vault is empty" }}
                            @endif
                        </h1>
                    </div>
                @endforelse
            </div>

            <div class="py-3">
                <flux:pagination :paginator="$vault_records" class="hidden sm:flex border-none! pt-0!" />

                <flux:pagination :paginator="$vault_records" simple class="sm:hidden border-none! pt-0!" />
            </div>
        </div>

        <div class="flex justify-center mt-1 py-4" wire:loading.flex
            wire:target='search,type,selected_ratings,selected_genres,sort_direction'>
            <x-large-spinner />
        </div>
    </div>
</div>
