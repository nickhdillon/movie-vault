<?php

declare(strict_types=1);

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
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
};
?>

<div class="relative">
    @foreach ($this->toasts as $toast)
        <div class="origin-top-right z-50 fixed right-0 m-5" x-cloak x-data="{ open: false }" x-show="open"
            x-init="$nextTick(() => {
                open = true;
                setTimeout(() => open = false, {{ $toast['timeout'] }})
            })" x-transition:enter="transition ease-out duration-500 transform"
            x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-out duration-500" x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-10">
            <div
                class="inline-flex min-w-80 rounded-lg text-sm text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-900 border dark:border-gray-800">
                <div class="flex p-2 w-full justify-between items-center">
                    <div class="flex items-center pl-1">
                        <svg class="w-4 h-4 shrink-0 fill-current opacity-80 mr-3" viewBox="0 0 16 16">
                            @switch($toast['status'])
                                @case('success')
                                    <path class="fill-emerald-500"
                                        d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zM7 11.4L3.6 8 5 6.6l2 2 4-4L12.4 6 7 11.4z">
                                    </path>
                                @break

                                @case('info')
                                    <path class="fill-blue-500"
                                        d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm1 12H7V7h2v5zM8 6c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z">
                                    </path>
                                @break

                                @case('warning')
                                    <path class="fill-amber-500"
                                        d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z">
                                    </path>
                                @break

                                @case('danger')
                                    <path class="fill-rose-500"
                                        d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm3.5 10.1l-1.4 1.4L8 9.4l-2.1 2.1-1.4-1.4L6.6 8 4.5 5.9l1.4-1.4L8 6.6l2.1-2.1 1.4 1.4L9.4 8l2.1 2.1z">
                                    </path>
                                @break

                                @default
                                    <path
                                        d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm1 12H7V7h2v5zM8 6c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z">
                                    </path>
                            @endswitch
                        </svg>
                        <div class="font-medium">{!! $toast['message'] !!}</div>
                    </div>

                    <flux:button icon="x-mark" variant="subtle" x-on:click="open = false"
                        class="h-6! w-6! ml-2 rounded-md! -mr-0.5 hover:bg-gray-100! dark:hover:bg-gray-700!" />
                </div>
            </div>
        </div>
    @endforeach
</div>
