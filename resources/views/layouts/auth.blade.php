<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BiblioTech') · BiblioTech</title>

    {{-- Polices locales (hors ligne) --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">

    {{-- Tailwind via Vite (mêmes couleurs/polices que le reste de l'application) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink font-sans antialiased">

<main class="min-h-screen flex flex-col items-center justify-center px-4 py-10">

    {{-- Logo --}}
    <a href="{{ route('login') }}" class="flex items-center gap-3 mb-6">
        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-brand-dark text-brand-light">
            <i class="fa-solid fa-book-open-reader text-xl"></i>
        </span>
        <span class="leading-tight">
            <span class="block font-serif text-2xl font-bold tracking-tight">BiblioTech</span>
            <span class="block text-xs text-ink-soft">Bibliothèque municipale</span>
        </span>
    </a>

    {{-- Cadre --}}
    <div class="w-full max-w-md overflow-hidden rounded-lg border border-ink-faint/30 border-t-4 border-t-brand bg-white shadow-sm">
        <div class="px-6 py-8 sm:px-8">

            <h1 class="font-serif text-2xl font-bold">@yield('heading')</h1>
            <p class="mt-1 text-sm text-ink-soft">@yield('subheading')</p>

            {{-- Messages flash --}}
            @if (session('success'))
                <div class="mt-6 flex items-start gap-3 rounded-md border border-brand/30 bg-brand-light px-4 py-3 text-sm text-brand-dark" role="status">
                    <i class="fa-solid fa-circle-check mt-0.5"></i>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mt-6 flex items-start gap-3 rounded-md border border-late/30 bg-late-light px-4 py-3 text-sm text-late" role="alert">
                    <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Erreurs de validation --}}
            @if ($errors->any())
                <div class="mt-6 rounded-md border border-late/30 bg-late-light px-4 py-3 text-sm text-late" role="alert">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>

        {{-- Pied du cadre : lien vers l'autre page --}}
        <div class="border-t border-ink-faint/20 bg-paper px-6 py-4 text-center text-sm text-ink-soft sm:px-8">
            @yield('footer')
        </div>
    </div>

    <p class="mt-6 text-xs text-ink-faint">Espace réservé au personnel</p>
</main>

</body>
<script src="{{ asset('assets/fontawesome/js/all.min.js') }}"></script>
</html>
