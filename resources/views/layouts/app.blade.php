<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BiblioTech') · BiblioTech</title>

    {{-- Polices --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">

    {{-- Tailwind (via Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-paper text-ink font-sans antialiased">

    @php
        $nav = [
            ['route' => 'dashboard', 'match' => 'dashboard', 'icon' => 'fa-gauge-high', 'label' => 'Tableau de bord'],
            ['route' => 'livres.index', 'match' => 'livres.*', 'icon' => 'fa-book', 'label' => 'Livres'],
            ['route' => 'adherents.index', 'match' => 'adherents.*', 'icon' => 'fa-users', 'label' => 'Adhérents'],
            ['route' => 'emprunts.index', 'match' => 'emprunts.*', 'icon' => 'fa-right-left', 'label' => 'Emprunts'],
        ];
    @endphp

    <div class="min-h-screen lg:flex">

        {{-- Barre latérale --}}
        <aside
            class="hidden lg:flex lg:w-64 lg:shrink-0 flex-col bg-brand-dark text-white lg:sticky lg:top-0 lg:h-screen">
            <div class="px-6 py-6 border-b border-white/10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <i class="fa-solid fa-book-open-reader text-2xl text-brand-light"></i>
                    <span class="font-serif text-xl font-bold tracking-tight">BiblioTech</span>
                </a>
                <p class="mt-1 text-xs text-white/60">Bibliothèque municipale</p>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1">
                @foreach ($nav as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm font-medium transition
                          {{ $active ? 'bg-white text-brand-dark' : 'text-white/80 hover:bg-white/10 hover:text-white' }}"
                        @if ($active) aria-current="page" @endif>
                        <i class="fa-solid {{ $item['icon'] }} w-5 text-center"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            @auth
                <div class="px-3 py-4 border-t border-white/10">
                    <p class="px-3 pb-2 text-xs text-white/60 truncate">
                        <i class="fa-solid fa-user-tie mr-1"></i> {{ auth()->user()->name }}
                    </p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 rounded-md px-3 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white">
                            <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Se déconnecter
                        </button>
                    </form>
                </div>
            @endauth
        </aside>

        <div class="flex-1 min-w-0">

            {{-- Barre mobile --}}
            <header class="lg:hidden flex items-center justify-between bg-brand-dark text-white px-4 py-3">
                <a href="{{ route('dashboard') }}" class="font-serif text-lg font-bold">
                    <i class="fa-solid fa-book-open-reader mr-2"></i>BiblioTech
                </a>
                <button type="button" id="menu-toggle" class="p-2 rounded hover:bg-white/10"
                    aria-label="Ouvrir le menu">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
            </header>
            <nav id="mobile-menu" class="hidden lg:hidden bg-brand-dark text-white px-3 pb-3 space-y-1">
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 rounded-md px-3 py-2.5 text-sm {{ request()->routeIs($item['match']) ? 'bg-white text-brand-dark' : 'text-white/80' }}">
                        <i class="fa-solid {{ $item['icon'] }} w-5 text-center"></i> {{ $item['label'] }}
                    </a>
                @endforeach
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="w-full text-left flex items-center gap-3 rounded-md px-3 py-2.5 text-sm text-white/80">
                            <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Se déconnecter
                        </button>
                    </form>
                @endauth
            </nav>

            <main class="px-4 sm:px-8 py-8 ">

                {{-- Messages flash --}}
                @if (session('success'))
                    <div class="mb-6 flex items-start gap-3 rounded-md border border-brand/30 bg-brand-light px-4 py-3 text-sm text-brand-dark"
                        role="status">
                        <i class="fa-solid fa-circle-check mt-0.5"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 flex items-start gap-3 rounded-md border border-late/30 bg-late-light px-4 py-3 text-sm text-late"
                        role="alert">
                        <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                {{-- Résumé des erreurs de validation --}}
                @if ($errors->any())
                    <div class="mb-6 rounded-md border border-late/30 bg-late-light px-4 py-3 text-sm text-late"
                        role="alert">
                        <p class="font-semibold mb-1">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i>
                            Le formulaire contient {{ $errors->count() }}
                            {{ \Illuminate\Support\Str::plural('erreur', $errors->count()) }} à corriger :
                        </p>
                        <ul class="list-disc pl-6 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.getElementById('menu-toggle')?.addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    <script src="{{ asset('assets/fontawesome/js/all.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
