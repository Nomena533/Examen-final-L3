@extends('layouts.auth')

@section('title', 'Connexion')
@section('heading', 'Connexion')
@section('subheading', 'Identifiez-vous avec votre compte bibliothécaire.')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5" novalidate>
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium mb-1">Adresse e-mail</label>
            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-ink-faint"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="email"
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

        <button type="submit"
            class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-brand px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-dark transition">
            <i class="fa-solid fa-right-to-bracket"></i> Se connecter
        </button>
    </form>
@endsection

@section('footer')
    Pas encore de compte ?
    <a href="{{ route('register') }}" class="font-medium text-brand hover:text-brand-dark hover:underline">S'inscrire</a>
@endsection
