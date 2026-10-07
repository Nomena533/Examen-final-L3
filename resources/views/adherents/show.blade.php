{{--
    Variables attendues :
      $adherent : Adherent avec ->load('emprunts.livre') (emprunts triés du plus récent au plus ancien)
--}}
@extends('layouts.app')
@section('title', $adherent->prenom . ' ' . $adherent->nom)

@section('content')
    @php
        $enCours  = $adherent->emprunts->whereNull('date_retour_effective');
        $retards  = $enCours->filter(fn ($e) => $e->date_retour_prevue->lt(today()));
    @endphp

    <div class="mb-6">
        <a href="{{ route('adherents.index') }}" class="text-sm text-brand hover:underline">
            <i class="fa-solid fa-arrow-left mr-1"></i> Retour aux adhérents
        </a>
    </div>

    <div class="flex flex-wrap items-start justify-between gap-4 mb-8">
        <div>
            <h1 class="font-serif text-3xl font-bold">{{ $adherent->prenom }} {{ $adherent->nom }}</h1>
            <dl class="mt-3 space-y-1 text-sm text-ink-soft">
                <div><i class="fa-solid fa-envelope w-5 text-ink-faint"></i> {{ $adherent->email }}</div>
                <div><i class="fa-solid fa-phone w-5 text-ink-faint"></i> {{ $adherent->telephone ?: 'Non renseigné' }}</div>
                <div><i class="fa-solid fa-calendar-check w-5 text-ink-faint"></i> Inscrit le {{ $adherent->date_inscription->format('d/m/Y') }}</div>
            </dl>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('emprunts.create', ['adherent_id' => $adherent->id]) }}"
               class="inline-flex items-center gap-2 rounded-md bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-dark">
                <i class="fa-solid fa-hand-holding-hand"></i> Enregistrer un emprunt
            </a>
            <a href="{{ route('adherents.edit', $adherent) }}"
               class="inline-flex items-center gap-2 rounded-md border border-ink-faint/50 bg-white px-4 py-2 text-sm hover:bg-paper">
                <i class="fa-solid fa-pen-to-square"></i> Modifier
            </a>
        </div>
    </div>

    @if ($retards->isNotEmpty())
        <div class="mb-6 flex items-start gap-3 rounded-md border border-late/30 bg-late-light px-4 py-3 text-sm text-late">
            <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
            <p>Cet adhérent a {{ $retards->count() }} {{ \Illuminate\Support\Str::plural('retard', $retards->count()) }} non rendu{{ $retards->count() > 1 ? 's' : '' }} : il ne peut pas emprunter tant que les livres ne sont pas retournés.</p>
        </div>
    @endif

    <h2 class="font-serif text-xl font-bold mb-3">
        Historique des emprunts
        <span class="ml-2 text-sm font-sans font-normal text-ink-soft">{{ $enCours->count() }} en cours sur 3 autorisés</span>
    </h2>

    <div class="overflow-x-auto rounded-lg bg-white border border-ink-faint/30">
        <table class="min-w-full text-sm">
            <thead class="bg-paper text-left text-ink-soft">
                <tr>
                    <th class="px-4 py-3 font-medium">Livre</th>
                    <th class="px-4 py-3 font-medium">Emprunté le</th>
                    <th class="px-4 py-3 font-medium">Retour prévu</th>
                    <th class="px-4 py-3 font-medium">Rendu le</th>
                    <th class="px-4 py-3 font-medium">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-faint/20">
                @forelse ($adherent->emprunts as $emprunt)
                    @php
                        $rendu = $emprunt->date_retour_effective !== null;
                        $enRetard = !$rendu && $emprunt->date_retour_prevue->lt(today());
                    @endphp
                    <tr class="{{ $enRetard ? 'bg-late-light/60' : '' }}">
                        <td class="px-4 py-3 font-medium">{{ $emprunt->livre->titre }}
                            <span class="block text-xs font-normal text-ink-soft">{{ $emprunt->livre->auteur }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $rendu ? $emprunt->date_retour_effective->format('d/m/Y') : '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($rendu)
                                <span class="inline-flex items-center gap-1.5 text-ink-soft"><i class="fa-solid fa-circle-check text-brand"></i> Rendu</span>
                            @elseif ($enRetard)
                                <span class="inline-flex items-center gap-1.5 font-medium text-late">
                                    <i class="fa-solid fa-clock"></i> En retard de {{ (int) $emprunt->date_retour_prevue->diffInDays(today()) }} j
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-brand-dark"><i class="fa-solid fa-book-open"></i> En cours</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-ink-soft">Cet adhérent n'a encore rien emprunté.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
