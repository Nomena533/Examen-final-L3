<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion · BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600&family=Source+Serif+4:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['"Public Sans"', 'system-ui', 'sans-serif'], serif: ['"Source Serif 4"', 'Georgia', 'serif'] },
            colors: { ink: { DEFAULT: '#1C2B2D', soft: '#4B5E60', faint: '#8A9B9D' }, paper: '#F1F4F3',
                      brand: { DEFAULT: '#1F5C4D', dark: '#164539', light: '#E3EFEB' }, late: { DEFAULT: '#B3261E', light: '#FBE9E7' } },
        } } }
    </script>
</head>
<body class="min-h-screen bg-paper font-sans text-ink antialiased lg:grid lg:grid-cols-2">

    <div class="hidden lg:flex flex-col justify-between bg-brand-dark p-12 text-white">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-book-open-reader text-3xl text-brand-light"></i>
            <span class="font-serif text-2xl font-bold">BiblioTech</span>
        </div>
        <div>
            <p class="font-serif text-4xl font-bold leading-tight">Le catalogue, les adhérents et les emprunts au même endroit.</p>
            <p class="mt-4 text-white/70 max-w-md">Fini les feuilles Excel : suivez les stocks, les retours et les retards de la bibliothèque municipale.</p>
        </div>
        <p class="text-sm text-white/50">Espace réservé au personnel</p>
    </div>

    <div class="flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">
            <h1 class="font-serif text-3xl font-bold">Connexion</h1>
            <p class="mt-1 text-sm text-ink-soft">Identifiez-vous avec votre compte bibliothécaire.</p>

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5" novalidate>
                @csrf

                @if ($errors->any())
                    <div class="rounded-md border border-late/30 bg-late-light px-4 py-3 text-sm text-late" role="alert">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Adresse e-mail</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-ink-faint"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                               class="w-full rounded-md border border-ink-faint/50 bg-white pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-1">Mot de passe</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-ink-faint"></i>
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                               class="w-full rounded-md border border-ink-faint/50 bg-white pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
                    </div>
                </div>

                <div class="flex grid-cols-2 gap-2">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-brand px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-dark">
                        <i class="fa-solid fa-right-to-bracket"></i> Se connecter
                    </button>
                    <a href="{{ route('register') }}"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-brand px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-dark">
                        <i class="fa-solid fa-right-to-bracket"></i> S'inscrire
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
