<?php

declare(strict_types=1);

namespace App\Livewire;

use Flux\Flux;
use App\Models\Vault;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Services\MovieVaultService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class VaultView extends Component
{
    use WithPagination;

    public $sort_by = null;

    public string $search = '';

    public string $type = '';

    public array $ratings = [];

    public array $selected_ratings = [];

    public array $genres = [];

    public array $selected_genres = [];

    public string $sort_direction = 'asc';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[On('clear-filters')]
    public function clearFilters(): void
    {
        $this->reset(['type', 'selected_ratings', 'selected_genres']);
    }

    public function updateVaultOrder(array $list): void
    {
        $this->resetPage();

        collect($list)->each(function (array $item): void {
            Vault::find($item['value'])->update(['sort' => $item['order']]);
        });

        $this->dispatch('show-toast', [
            'status' => 'success',
            'message' => "Items successfully sorted"
        ]);
    }

    public function sort(string $column): void
    {
        if ($this->sort_by === $column) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $column;
            $this->sort_direction = 'asc';
        }
    }

    public function addToWishlist(Vault $vault): void
    {
        $vault?->update(['on_wishlist' => true]);

        $name = $vault->title ?? $vault->name;

        $this->dispatch('show-toast', [
            'status' => 'success',
            'message' => "Successfully added {$name} to your wishlist"
        ]);

        Flux::modal("$vault->id-wishlist")->close();
    }

    public function delete(Vault $vault): void
    {
        $this->dispatch('show-toast', [
            'status' => 'success',
            'message' => "Successfully removed {$vault->title} from your vault"
        ]);

        Flux::modal("$vault->id-delete")->close();

        $vault?->delete();
    }

    public function render(): View
    {
        $this->ratings = MovieVaultService::getRatings();

        $this->genres = MovieVaultService::getGenres();

        return view('livewire.vault-view', [
            'vault_records' => auth()
                ->user()
                ->vaults()
                ->whereOnWishlist(false)
                ->when(strlen($this->search) >= 1, function (Builder $query): void {
                    $query->where(function (Builder $query): void {
                        $query->whereLike('title', "%$this->search%")
                            ->orWhereLike('original_title', "%$this->search%")
                            ->orWhereLike('actors', "%$this->search%");
                    });
                })
                ->when($this->type, function (Builder $query): void {
                    $query->where('vault_type', $this->type);
                })
                ->when($this->selected_ratings, function (Builder $query): void {
                    foreach ($this->selected_ratings as $rating) {
                        $query->where('rating', $rating);
                    }
                })
                ->when($this->selected_genres, function (Builder $query): void {
                    foreach ($this->selected_genres as $genre) {
                        $query->where('genres', 'LIKE', "%$genre%");
                    }
                })
                ->orderBy($this->sort_by, $this->sort_direction)
                ->orderBy('sort')
                ->get(),
        ]);
    }
}
