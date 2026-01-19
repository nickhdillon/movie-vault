<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item href="{{ route('settings.profile') }}" wire:navigate
                class="hover:bg-gray-100! data-current:bg-gray-100! dark:hover:bg-gray-800! dark:data-current:bg-gray-800! data-current:dark:text-blue-400!"
            >
                Profile
            </flux:navlist.item>

            <flux:navlist.item href="{{ route('settings.password') }}" wire:navigate
                class="hover:bg-gray-100! data-current:bg-gray-100! dark:hover:bg-gray-800! dark:data-current:bg-gray-800! data-current:dark:text-blue-400!"
            >
                Password
            </flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
