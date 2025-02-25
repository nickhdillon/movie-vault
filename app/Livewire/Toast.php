<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Contracts\View\View;

class Toast extends Component
{
    public array $toasts = [];

    public array $defaults = [
        'timeout' => 5000,
        'message' => 'Success',
        'status' => 'success',
    ];

    public function mount(): void
    {
        if (session()->has('toast')) {
            $this->toasts[] = array_merge($this->defaults, session()->get('toast'));
        }
    }

    #[On('show-toast')]
    public function showToast(array $toast_data): void
    {
        $this->toasts[] = array_merge($this->defaults, $toast_data);
    }

    public function render(): View
    {
        return view('livewire.toast');
    }
}
