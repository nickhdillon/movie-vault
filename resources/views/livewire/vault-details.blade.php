@use('App\Services\MovieVaultService', 'MovieVaultService')

<div class="w-full mx-auto overflow-y-hidden max-w-7xl">
    <div class="flex flex-col-reverse sm:items-center justify-between gap-4 sm:flex-row">
        <flux:heading size="xl" level="1">
            {{ $vault->title }}
        </flux:heading>

        <div class="flex items-center w-full space-x-2 sm:w-auto sm:flex-row">
            <flux:button variant="primary" size="sm" icon="film" href="{{ route('my-vault') }}" wire:navigate
                class="w-full sm:w-auto">
                Vault
            </flux:button>

            <flux:button variant="primary" size="sm" wire:navigate href="{{ route('wishlist') }}"
                class="w-full sm:w-auto">
                <flux:icon icon="heart" variant="outline" class="w-4 h-4" />

                Wishlist
            </flux:button>
        </div>
    </div>

    <div
        class="flex flex-col w-full mt-2 overflow-hidden bg-white border rounded-lg sm:mt-4 md:flex-row border-slate-200 dark:border-slate-700 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100">
        <div class="relative w-full md:w-96 h-96 md:h-auto">
            <img class="absolute inset-0 object-cover w-full h-full"
                src="{{ 'https://image.tmdb.org/t/p/w500/' . $vault->poster_path ?? $vault->backdrop_path . '?include_adult=false&language=en-US&page=1' }}"
                alt="{{ $vault->title }}" />
        </div>

        <div class="relative flex flex-col justify-between w-full p-4 -mb-3! space-y-3">
            <p>
                <span class="font-semibold">
                    Title:
                </span>

                {{ $vault->title }}
            </p>

            <p>
                <span class="font-semibold">
                    Type:
                </span>

                {{ $vault->vault_type === 'tv' ? 'TV Show' : Str::title($vault->vault_type) }}
            </p>

            <p>
                <span class="font-semibold">
                    Overview:
                </span>

                {{ $vault->overview }}
            </p>

            <p>
                <span class="font-semibold">
                    {{ $vault->release_date ? 'Release Date: ' : 'First Air Date: ' }}
                </span>

                {{ MovieVaultService::formatDate($vault->release_date ?? $vault->first_air_date) }}
            </p>

            <p>
                <span class="font-semibold">
                    Genres:
                </span>

                {{ Str::replace(',', ', ', $vault->genres) }}
            </p>

            <p>
                <span class="font-semibold">
                    Rating:
                </span>

                {{ $vault->rating }}

                @isset($vault->imdb_id)
                    -

                    <a class="text-sm font-medium text-indigo-500 duration-200 ease-in-out hover:text-indigo-600 dark:hover:text-indigo-400"
                        href="https://www.imdb.com/title/{{ $vault->imdb_id }}/parentalguide" target="_blank">
                        <span class="inline-flex items-center">
                            View parents guide

                            <flux:icon.arrow-up-right class="w-3 h-3 stroke-3 ml-0.5" />
                        </span>
                    </a>
                @endisset
            </p>

            @isset($vault->runtime)
                <p>
                    <span class="font-semibold">
                        Length:
                    </span>

                    {{ MovieVaultService::formatRuntime($vault->runtime) }}
                </p>
            @endisset

            @isset($vault->seasons)
                <p>
                    <span class="font-semibold">
                        Seasons:
                    </span>

                    {{ $vault->seasons }}
                </p>
            @endisset

            <p>
                <span class="font-semibold">
                    Actors:
                </span>

                {{ Str::replace(',', ', ', $vault->actors) ?: 'No actors found' }}
            </p>

            <div class="flex items-center sm:bottom-2 sm:right-0 sm:p-4 sm:absolute sm:pt-0 -ml-2">
                <flux:modal.trigger name="add-to-{{ $vault->id }}">
                    <flux:button variant="subtle" class="w-3! h-8!">
                        <flux:icon.plus class="w-5! h-5! text-slate-600! dark:text-slate-200!" />
                    </flux:button>
                </flux:modal.trigger>

                <flux:modal name="add-to-{{ $vault->id }}" class="w-90 sm:w-120!"
                    x-on:close-modal.window="$flux.modal('add-to-{{ $vault->id }}').close()">
                    <flux:heading size="lg">
                        Add to

                        {{ $vault->on_wishlist ? 'vault' : 'wishlist' }}?
                    </flux:heading>

                    <flux:subheading>
                        Are you sure you want to add

                        <span class="font-semibold text-indigo-500">
                            '{{ $vault->title }}'
                        </span>

                        to your

                        {{ $vault->on_wishlist ? 'vault' : 'wishlist' }}?
                    </flux:subheading>

                    <div class="flex mt-6 -mb-1">
                        <flux:spacer />

                        <div class="space-x-1 flex items-center">
                            <flux:modal.close>
                                <flux:button size="sm" variant="ghost">
                                    Cancel
                                </flux:button>
                            </flux:modal.close>

                            <form
                                wire:submit="{{ $vault->on_wishlist ? 'addToVault(' . $vault->id . ')' : 'addToWishlist(' . $vault->id . ')' }}">
                                <flux:button size="sm" type="submit" variant="primary">
                                    Confirm
                                </flux:button>
                            </form>
                        </div>
                    </div>
                </flux:modal>

                <flux:modal.trigger name="delete-{{ $vault->id }}">
                    <flux:button variant="subtle" class="w-3! h-8!">
                        <flux:icon.trash class="text-red-500 w-5! h-5!" />
                    </flux:button>
                </flux:modal.trigger>

                <flux:modal name="delete-{{ $vault->id }}" class="w-90 sm:w-120!"
                    x-on:close-modal.window="$flux.modal('delete-{{ $vault->id }}').close()">
                    <flux:heading size="lg">
                        Remove from

                        {{ $vault->on_wishlist ? 'wishlist' : 'vault' }}
                    </flux:heading>

                    <flux:subheading>
                        Are you sure you want to remove

                        <span class="font-semibold text-red-500">
                            '{{ $vault->title }}'
                        </span>

                        from your

                        {{ $vault->on_wishlist ? 'wishlist' : 'vault' }}?
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
