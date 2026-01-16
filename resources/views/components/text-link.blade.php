<a
    {{ $attributes->merge(['class' => 'underline text-sm decoration-gray-400 underline-offset-2 duration-300 ease-out hover:decoration-gray-700 text-gray-900 dark:text-gray-200 dark:hover:decoration-gray-100']) }}
    wire:navigate
>
    {{ $slot }}
</a>
