@extends('layouts.app')
@section('title', 'Ajouter un livre')

@section('content')
    <div class="mb-6">
        <a href="{{ route('livres.index') }}" class="text-sm text-brand hover:underline">
            <i class="fa-solid fa-arrow-left mr-1"></i> Retour au catalogue
        </a>
        <h1 class="mt-2 font-serif text-3xl font-bold">Ajouter un livre</h1>
    </div>

    <form method="POST" action="{{ route('livres.store') }}" novalidate
          class=" rounded-lg bg-white border border-ink-faint/30 p-6">
        @csrf
        @include('livres._form')

        <div class="mt-8 flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-dark">
                <i class="fa-solid fa-floppy-disk"></i> Enregistrer le livre
            </button>
            <a href="{{ route('livres.index') }}" class="text-sm text-ink-soft hover:underline">Annuler</a>
        </div>
    </form>
@endsection
