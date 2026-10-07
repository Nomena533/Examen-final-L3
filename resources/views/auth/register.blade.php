@extends('layouts.auth')

@section('title', 'Inscription')
@section('heading', 'Inscription')
@section('subheading', 'Créez votre compte en tant que bibliothécaire.')

@section('content')
    <form method="POST" action="{{ route('registerPost') }}" class="mt-6 space-y-5" novalidate>
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium mb-1">Nom</label>
            <div class="relative">
                <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-ink-faint"></i>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                    autocomplete="name"
                    class="w-full rounded-md border border-ink-faint/50 bg-white pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium mb-1">Adresse e-mail</label>
            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-ink-faint"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    autocomplete="email"
                    class="w-full rounded-md border border-ink-faint/50 bg-white pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium mb-1">Mot de passe</label>
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-ink-faint"></i>
                <input type="password" id="password" name="password" required autocomplete="new-password"
                    class="w-full rounded-md border border-ink-faint/50 bg-white pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
            </div>
        </div>

        <button type="submit"
            class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-brand px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-dark transition">
            <i class="fa-solid fa-user-plus"></i> S'inscrire
        </button>
    </form>
@endsection

@section('footer')
    Déjà un compte ?
    <a href="{{ route('login') }}" class="font-medium text-brand hover:text-brand-dark hover:underline">Se connecter</a>
@endsection
