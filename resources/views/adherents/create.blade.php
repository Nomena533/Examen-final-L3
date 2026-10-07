@extends('layouts.app')
@section('title', 'Ajouter un adhérent')

@section('content')
    <div class="mb-6">
        <a href="{{ route('adherents.index') }}" class="text-sm text-brand hover:underline">
            <i class="fa-solid fa-arrow-left mr-1"></i> Retour aux adhérents
        </a>
        <h1 class="mt-2 font-serif text-3xl font-bold">Ajouter un adhérent</h1>
    </div>

    <form method="POST" action="{{ route('adherents.store') }}" novalidate
          class="max-w-3xl rounded-lg bg-white border border-ink-faint/30 p-6">
        @csrf
        @include('adherents._form')

        <div class="mt-8 flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-dark">
                <i class="fa-solid fa-user-plus"></i> Enregistrer l'adhérent
            </button>
            <a href="{{ route('adherents.index') }}" class="text-sm text-ink-soft hover:underline">Annuler</a>
        </div>
    </form>
@endsection
