<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

new class extends Component
{
    public ?User $user = null;

    public Vault $vault;

    public ?string $previous_url = '';

    public function addToVault(Vault $vault): RedirectResponse|Redirector
    {
        $vault?->update(['on_wishlist' => false]);

        $this->dispatch('show-toast', [
            'status' => 'success',
            'message' => "Successfully added {$vault->title} to your vault"
        ]);

        return redirect(route('my-vault'));
    }

    public function addToWishlist(Vault $vault): RedirectResponse|Redirector
    {
        $vault?->update(['on_wishlist' => true]);

        $this->dispatch('show-toast', [
            'status' => 'success',
            'message' => "Successfully added {$vault->title} to your wishlist"
        ]);

        return redirect(route('wishlist'));
    }

    public function delete(Vault $vault): RedirectResponse|Redirector
    {
        $page = $vault->on_wishlist ? 'wishlist' : 'vault';

        $route = $vault->on_wishlist ? 'wishlist' : 'my-vault';

        session()->flash('toast', [
            'status' => 'success',
            'message' => "Successfully removed {$vault->title} from your {$page}"
        ]);

        $vault?->delete();

        return redirect(route("{$route}"));
    }

    public function render(): View
    {
        return $this->view();
    }
};