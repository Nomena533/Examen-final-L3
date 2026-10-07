
@extends('layouts.app')
@section('title', 'Enregistrer un emprunt')

@section('content')
    @php
        $livreChoisi = old('livre_id', request('livre_id'));
        $adherentChoisi = old('adherent_id', request('adherent_id'));
    @endphp

    <div class="mb-6">
        <a href="{{ route('emprunts.index') }}" class="text-sm text-brand hover:underline">
            <i class="fa-solid fa-arrow-left mr-1"></i> Retour aux emprunts
        </a>
        <h1 class="mt-2 font-serif text-3xl font-bold">Enregistrer un emprunt</h1>
    </div>

    @if ($livres->isEmpty())
        <div class="mb-6 flex items-start gap-3 rounded-md border border-late/30 bg-late-light px-4 py-3 text-sm text-late">
            <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
            <p>Aucun livre n'est disponible actuellement : tous les exemplaires sont empruntés.</p>
        </div>
    @endif

    <form method="POST" action="{{ route('emprunts.store') }}" novalidate
        class="rounded-lg bg-white border border-ink-faint/30 p-6">
        @csrf

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="livre_id" class="block text-sm font-medium mb-1">Livre <span class="text-late"
                        aria-hidden="true">*</span></label>
                <select id="livre_id" name="livre_id" required
                    class="w-full rounded-md border bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand
                               {{ $errors->has('livre_id') ? 'border-late' : 'border-ink-faint/50' }}">
                    <option value="">Choisir un livre disponible…</option>
                    @foreach ($livres as $livre)
                        <option value="{{ $livre->id }}" @selected($livreChoisi == $livre->id)>
                            {{ $livre->titre }} — {{ $livre->auteur }} ({{ $livre->quantite_disponible }} dispo.)
                        </option>
                    @endforeach
                </select>
                @error('livre_id')
                    <p class="mt-1 text-xs text-late"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="adherent_id" class="block text-sm font-medium mb-1">Adhérent <span class="text-late"
                        aria-hidden="true">*</span></label>
                <select id="adherent_id" name="adherent_id" required
                    class="w-full rounded-md border bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand
                               {{ $errors->has('adherent_id') ? 'border-late' : 'border-ink-faint/50' }}">
                    <option value="">Choisir un adhérent…</option>
                    @foreach ($adherents as $adherent)
                        <option value="{{ $adherent->id }}" @selected($adherentChoisi == $adherent->id)>
                            {{ $adherent->nom }} {{ $adherent->prenom }} — {{ $adherent->email }}
                        </option>
                    @endforeach
                </select>
                @error('adherent_id')
                    <p class="mt-1 text-xs text-late"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}
                    </p>
                @enderror
            </div>

            @include('partials.input', [
                'name' => 'date_emprunt',
                'label' => 'Date d\'emprunt',
                'type' => 'date',
                'required' => true,
                'value' => now()->format('Y-m-d'),
            ])
            @include('partials.input', [
                'name' => 'date_retour_prevue',
                'label' => 'Retour prévu',
                'type' => 'date',
                'required' => true,
                'value' => now()->addDays(14)->format('Y-m-d'),
                'hint' => 'Durée par défaut : 14 jours.',
            ])
        </div>

        <div class="mt-6 rounded-md bg-paper px-4 py-3 text-xs text-ink-soft">
            <p class="font-medium text-ink mb-1"><i class="fa-solid fa-circle-info mr-1"></i> Règles appliquées</p>
            <p>Un adhérent ne peut pas avoir plus de 3 emprunts en cours, ni emprunter s'il a un retard non rendu. Un livre
                n'est empruntable que s'il en reste en stock.</p>
        </div>

        <div class="mt-8 flex items-center gap-3">
            <button type="submit" @disabled($livres->isEmpty())
                class="inline-flex items-center gap-2 rounded-md bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-dark disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fa-solid fa-hand-holding-hand"></i> Enregistrer l'emprunt
            </button>
            <a href="{{ route('emprunts.index') }}" class="text-sm text-ink-soft hover:underline">Annuler</a>
        </div>
    </form>
@endsection
