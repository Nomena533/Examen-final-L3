{{--
    Variables attendues :
      $adherents : Collection (ou paginator) d'Adherent
                   idéalement avec withCount(['emprunts as emprunts_en_cours_count' => fn($q) => $q->whereNull('date_retour_effective')])
--}}
@extends('layouts.app')
@section('title', 'Adhérents')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="font-serif text-3xl font-bold">Adhérents</h1>
            <p class="text-sm text-ink-soft mt-1">
                {{ method_exists($adherents, 'total') ? $adherents->total() : $adherents->count() }} inscrits
            </p>
        </div>
        <a href="{{ route('adherents.create') }}"
            class="inline-flex items-center gap-2 rounded-md bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-dark">
            <i class="fa-solid fa-user-plus"></i> Ajouter un adhérent
        </a>
    </div>

    <div class="overflow-x-auto rounded-lg bg-white border border-ink-faint/30">
        <table class="min-w-full text-sm">
            <thead class="bg-paper text-left text-ink-soft">
                <tr>
                    <th class="px-4 py-3 font-medium">Nom</th>
                    <th class="px-4 py-3 font-medium">E-mail</th>
                    <th class="px-4 py-3 font-medium">Téléphone</th>
                    <th class="px-4 py-3 font-medium">Inscrit le</th>
                    <th class="px-4 py-3 font-medium">Emprunts en cours</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-faint/20">
                @forelse ($adherents as $adherent)
                    <tr class="hover:bg-paper/60">
                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('adherents.show', $adherent) }}" class="hover:text-brand hover:underline">
                                {{ $adherent->prenom }} {{ $adherent->nom }}
                            </a>
                        </td>
                        <td class="px-4 py-3">{{ $adherent->email }}</td>
                        <td class="px-4 py-3 text-ink-soft">{{ $adherent->telephone ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $adherent->date_inscription->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @php $enCours = $adherent->emprunts_en_cours_count ?? 0; @endphp
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium
                                         {{ $enCours >= 3 ? 'bg-late-light text-late' : ($enCours > 0 ? 'bg-brand-light text-brand-dark' : 'bg-paper text-ink-soft') }}">
                                {{ $enCours }} / 3
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('adherents.show', $adherent) }}" title="Voir la fiche"
                                    class="rounded p-2 text-ink-soft hover:bg-brand-light hover:text-brand-dark">
                                    <i class="fa-solid fa-id-card"></i><span class="sr-only">Voir la fiche</span>
                                </a>
                                <a href="{{ route('adherents.edit', $adherent) }}" title="Modifier"
                                    class="rounded p-2 text-ink-soft hover:bg-brand-light hover:text-brand-dark">
                                    <i class="fa-solid fa-pen-to-square"></i><span class="sr-only">Modifier</span>
                                </a>
                                <form method="POST" action="{{ route('adherents.destroy', $adherent) }}"
                                    onsubmit="return confirm('Supprimer l\'adhérent {{ addslashes($adherent->prenom . ' ' . $adherent->nom) }} ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Supprimer"
                                        class="rounded p-2 text-ink-soft hover:bg-late-light hover:text-late">
                                        <i class="fa-solid fa-trash"></i><span class="sr-only">Supprimer</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-ink-soft">
                            <i class="fa-solid fa-users text-3xl text-ink-faint mb-3"></i>
                            <p>Aucun adhérent inscrit pour le moment.</p>
                            <a href="{{ route('adherents.create') }}"
                                class="mt-2 inline-block text-brand hover:underline">Inscrire le premier adhérent</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (method_exists($adherents, 'links'))
        <div class="mt-6">{{ $adherents->links() }}</div>
    @endif
@endsection
