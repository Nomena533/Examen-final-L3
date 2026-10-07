{{--
    Variables attendues :
      $emprunts : Collection (ou paginator) d'Emprunt EN COURS (date_retour_effective null), avec ->with(['livre', 'adherent']),
                  triés par date_retour_prevue croissante (les retards en premier)
--}}
@extends('layouts.app')
@section('title', 'Emprunts en cours')

@section('content')
    @php
        $retards = $emprunts->filter(fn($e) => $e->date_retour_prevue->lt(today()));
    @endphp

    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="font-serif text-3xl font-bold">Emprunts en cours</h1>
            <p class="text-sm text-ink-soft mt-1">
                {{ $emprunts->count() }} en cours,
                <span class="{{ $retards->isNotEmpty() ? 'font-medium text-late' : '' }}">{{ $retards->count() }} en
                    retard</span>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('emprunts.export') }}"
                class="inline-flex items-center gap-2 rounded-md border border-ink-faint/50 bg-white px-4 py-2 text-sm hover:bg-paper">
                <i class="fa-solid fa-file-csv"></i> Exporter en CSV
            </a>
            <a href="{{ route('emprunts.create') }}"
                class="inline-flex items-center gap-2 rounded-md bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-dark">
                <i class="fa-solid fa-plus"></i> Enregistrer un emprunt
            </a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg bg-white border border-ink-faint/30">
        <table class="min-w-full text-sm">
            <thead class="bg-paper text-left text-ink-soft">
                <tr>
                    <th class="px-4 py-3 font-medium">Livre</th>
                    <th class="px-4 py-3 font-medium">Adhérent</th>
                    <th class="px-4 py-3 font-medium">Emprunté le</th>
                    <th class="px-4 py-3 font-medium">Retour prévu</th>
                    <th class="px-4 py-3 font-medium">Statut</th>
                    <th class="px-4 py-3 font-medium text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-faint/20">
                @forelse ($emprunts as $emprunt)
                    @php $enRetard = $emprunt->date_retour_prevue->lt(today()); @endphp
                    <tr class="{{ $enRetard ? 'bg-late-light/70' : 'hover:bg-paper/60' }}">
                        <td class="px-4 py-3 font-medium">
                            {{ $emprunt->livre->titre }}
                            <span class="block text-xs font-normal text-ink-soft">{{ $emprunt->livre->auteur }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('adherents.show', $emprunt->adherent) }}"
                                class="hover:text-brand hover:underline">
                                {{ $emprunt->adherent->prenom }} {{ $emprunt->adherent->nom }}
                            </a>
                        </td>
                        <td class="px-4 py-3">{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 {{ $enRetard ? 'font-semibold text-late' : '' }}">
                            {{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if ($enRetard)
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-late px-2.5 py-0.5 text-xs font-medium text-white">
                                    <i class="fa-solid fa-clock"></i> Retard de
                                    {{ (int) $emprunt->date_retour_prevue->diffInDays(today()) }} j
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-brand-light px-2.5 py-0.5 text-xs font-medium text-brand-dark">
                                    <i class="fa-solid fa-book-open"></i> Dans les temps
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('emprunts.retour', $emprunt) }}"
                                onsubmit="return confirm('Enregistrer le retour de « {{ addslashes($emprunt->livre->titre) }} » ?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-md border border-brand/40 px-3 py-1.5 text-xs font-medium text-brand-dark hover:bg-brand hover:text-white">
                                    <i class="fa-solid fa-rotate-left"></i> Enregistrer le retour
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-ink-soft">
                            <i class="fa-solid fa-circle-check text-3xl text-brand mb-3"></i>
                            <p>Aucun emprunt en cours : tous les livres sont rentrés.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
