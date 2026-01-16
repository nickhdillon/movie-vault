<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="h-screen bg-white dark:bg-slate-800">
    <flux:header container class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-900">
        <a href="{{ route('my-vault') }}" wire:navigate class="flex items-center space-x-2">
            <x-app-logo class="size-8" />
        </a>

        <flux:spacer />

        @if (auth()->user())
            <flux:dropdown position="top" align="end" class="overflow-x-none!">
                @if (auth()->user()->avatar)
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        :avatar="Storage::disk('s3')->url('avatars/' . auth()->user()->avatar)"
                        :chevron="false"
                        circle
                    />
                @else
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        :chevron="false"
                        circle
                    />
                @endif

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <div
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-slate-200 text-black dark:bg-slate-800 dark:text-white">
                                        @if (auth()->user()->avatar)
                                            <img
                                                src="{{ Storage::disk('s3')->url('avatars/' . auth()->user()->avatar) }}" />
                                        @else
                                            <p class="text-xs">{{ auth()->user()->initials() }}</p>
                                        @endif
                                    </div>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:radio.group x-data variant="segmented" size="sm" x-model="$flux.appearance">
                        <flux:radio value="light" icon="sun" />
                        <flux:radio value="dark" icon="moon" />
                        <flux:radio value="system" icon="computer-desktop" />
                    </flux:radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>{{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        @else
            <flux:navlist>
                <flux:navlist.item href="{{ route('login') }}" wire:navigate
                    class="hover:bg-slate-100! data-current:bg-slate-100! dark:hover:bg-slate-700! dark:data-current:bg-slate-700! data-current:dark:text-indigo-400!">
                    Login
                </flux:navlist.item>
            </flux:navlist>
        @endif

        @livewire('toast')
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>
