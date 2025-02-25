@props(['ratings', 'genres'])

<div x-data="{
    showSortBy: true,
    showTypes: true,
    showRatings: true,
    showGenres: true,
    slideOverOpen: false,
    totalFilters() {
        total = $wire.selected_ratings.length + $wire.selected_genres.length;

        if ($wire.sort_direction) total++;

        if ($wire.type) total++;

        return total;
    },
}" class="relative z-20 w-auto h-auto">
    <flux:modal.trigger name="filters">
        <div class="relative inline-block">
            <flux:button icon="funnel" x-on:click="slideOverOpen = true" />

            <span x-cloak x-show="totalFilters() > 0"
                class="absolute top-0 right-0 flex items-center justify-center w-[19px] h-[19px] -mt-2 -mr-2 text-xs bg-indigo-500 rounded-full text-slate-200"
                x-text="totalFilters()">
            </span>
        </div>
    </flux:modal.trigger>

    <flux:modal name="filters" variant="flyout" class="space-y-6 px-5! w-[250px]!">
        <div class="flex mt-4 items-center justify-between">
            <flux:heading size="lg">
                Filters
            </flux:heading>

            <div class="flex items-center space-x-1">
                <button x-cloak x-show="totalFilters() > 1"
                    class="px-2 text-sm font-medium text-slate-800 dark:text-slate-200 duration-200 ease-in-out rounded hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700"
                    x-on:click="$dispatch('clear-filters')">
                    Clear all
                </button>
            </div>
        </div>

        <div class="relative flex-1 mt-2 space-y-3">
            <div>
                <div class="flex pb-0.5 items-center justify-between text-sm font-medium">
                    <p>
                        Sort By
                    </p>

                    <flux:button variant="subtle" icon="chevron-down"
                        class="!h-6 !w-6 hover:!bg-slate-200 dark:hover:!bg-slate-700 !-mr-0.5  hover:text-slate-800 dark:hover:text-slate-200"
                        x-bind:class="showSortBy ? 'rotate-180' : ''" x-on:click="showSortBy = !showSortBy" />
                </div>

                <div x-collapse x-show="showSortBy" class="pt-2 border-t border-slate-300 dark:border-slate-700">
                    <flux:radio.group wire:model.live="sort_direction" class="space-y-2!">
                        <flux:radio value="asc" label="A-Z" />
                        <flux:radio value="desc" label="Z-A" />
                    </flux:radio.group>
                </div>
            </div>

            <div class="mb-4">
                <div class="pb-0.5 text-sm font-medium flex items-center justify-between">
                    <p>
                        Types
                    </p>

                    <div class="flex items-center justify-between">
                        <button x-cloak x-show="$wire.type"
                            class="px-2 text-sm font-medium duration-200 ease-in-out rounded hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700"
                            x-on:click="$wire.set('type', '')">
                            Clear
                        </button>

                        <flux:button variant="subtle" icon="chevron-down"
                            class="!h-6 !w-6 hover:!bg-slate-200 dark:hover:!bg-slate-700 !-mr-0.5  hover:text-slate-800 dark:hover:text-slate-200"
                            x-bind:class="showTypes ? 'rotate-180' : ''" x-on:click="showTypes = !showTypes" />
                    </div>
                </div>

                <div x-collapse x-show="showTypes"
                    class="pt-2 space-y-2 border-t border-slate-300 dark:border-slate-700">
                    <flux:radio.group wire:model.live="type" class="space-y-2!">
                        <flux:radio value="movie" label="Movie" />
                        <flux:radio value="tv" label="TV Show" />
                    </flux:radio.group>
                </div>
            </div>

            <div>
                <div class="pb-0.5 text-sm font-medium flex items-center justify-between">
                    <p>
                        Ratings
                    </p>

                    <div class="flex items-center justify-between">
                        <button x-cloak x-show="$wire.selected_ratings.length > 0"
                            class="px-2 text-sm font-medium duration-200 ease-in-out rounded hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700"
                            x-on:click="$wire.set('selected_ratings', [])">
                            Clear
                        </button>

                        <flux:button variant="subtle" icon="chevron-down"
                            class="!h-6 !w-6 hover:!bg-slate-200 dark:hover:!bg-slate-700 !-mr-0.5  hover:text-slate-800 dark:hover:text-slate-200"
                            x-bind:class="showRatings ? 'rotate-180' : ''" x-on:click="showRatings = !showRatings" />
                    </div>
                </div>

                <div x-collapse x-show="showRatings" class="pt-2 border-t border-slate-300 dark:border-slate-700">
                    @foreach ($ratings as $rating)
                        <flux:checkbox wire:model.live="selected_ratings" label="{{ $rating }}" />
                    @endforeach
                </div>
            </div>

            <div>
                <div class="pb-0.5 text-sm font-medium flex items-center justify-between">
                    <p>
                        Genres
                    </p>

                    <div class="flex items-center justify-between">
                        <button x-cloak x-show="$wire.selected_genres.length > 0"
                            class="px-2 text-sm font-medium duration-200 ease-in-out rounded hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700"
                            x-on:click="$wire.set('selected_genres', [])">
                            Clear
                        </button>

                        <flux:button variant="subtle" icon="chevron-down"
                            class="!h-6 !w-6 hover:!bg-slate-200 dark:hover:!bg-slate-700 !-mr-0.5  hover:text-slate-800 dark:hover:text-slate-200"
                            x-bind:class="showGenres ? 'rotate-180' : ''" x-on:click="showGenres = !showGenres" />
                    </div>
                </div>

                <div x-collapse x-show="showGenres" class="pt-2 border-t border-slate-300 dark:border-slate-700">
                    @foreach ($genres as $genre)
                        <flux:checkbox wire:model.live="selected_genres" label="{{ $genre }}" />
                    @endforeach
                </div>
            </div>
        </div>
    </flux:modal>
</div>
