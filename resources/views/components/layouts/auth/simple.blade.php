<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-slate-950 dark:to-slate-900">
    <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-sm flex-col gap-2">
            <a href="{{ route('my-vault') }}" class="flex flex-col items-center gap-2 font-medium">
                <span class="flex mb-1 items-center justify-center rounded-md">
                    <img src="{{ asset('icon.svg') }}" class="w-30" />
                </span>
                <span class="sr-only">{{ config('app.name', 'Movie Vault') }}</span>
            </a>

            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>
