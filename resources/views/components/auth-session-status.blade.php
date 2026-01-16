@props([
    'status',
])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-blue-500']) }}>
        {{ $status }}
    </div>
@endif
