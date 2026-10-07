{{--
    Variables attendues :
      $livres      : LengthAwarePaginator de Livre (paginate(10)->withQueryString())
      $categories  : Collection des catégories distinctes (pour le filtre)
    Paramètres GET : q, categorie, disponibles (=1)
--}}
@extends('layouts.app')
@section('title', 'Livres')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="font-serif text-3xl font-bold">Catalogue des livres</h1>
            <p class="text-sm text-ink-soft mt-1">{{ $livres->total() }} {{ \Illuminate\Support\Str::plural('titre', $livres->total()) }} au catalogue</p>
        </div>
        <a href="{{ route('livres.create') }}"
           class="inline-flex items-center gap-2 rounded-md bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-dark">
            <i class="fa-solid fa-plus"></i> Ajouter un livre
        </a>
    </div>

    {{-- Recherche et filtres (F6) --}}
    <form method="GET" action="{{ route('livres.index') }}"
          class="mb-6 grid gap-3 rounded-lg bg-white border border-ink-faint/30 p-4 md:grid-cols-[1fr_14rem_auto_auto] md:items-center">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-ink-faint"></i>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher par titre ou auteur"
                   class="w-full rounded-md border border-ink-faint/50 pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand">
        </div>

        <select name="categorie" aria-label="Catégorie"
                class="rounded-md border border-ink-faint/50 px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand/40">
            <option value="">Toutes les catégories</option>
            @foreach ($categories as $categorie)
                <option value="{{ $categorie }}" @selected(request('categorie') === $categorie)>{{ $categorie }}</option>
            @endforeach
        </select>

        <label class="inline-flex items-center gap-2 text-sm cursor-pointer">
            <input type="checkbox" name="disponibles" value="1" @checked(request()->boolean('disponibles'))
                   class="rounded border-ink-faint text-brand focus:ring-brand/40">
            Disponibles uniquement
        </label>

        <div class="flex gap-2">
            <button type="submit" class="rounded-md bg-ink px-4 py-2 text-sm font-medium text-white hover:bg-black">Filtrer</button>
            @if (request()->hasAny(['q', 'categorie', 'disponibles']))
                <a href="{{ route('livres.index') }}" class="rounded-md border border-ink-faint/50 px-3 py-2 text-sm text-ink-soft hover:bg-paper"
                   title="Réinitialiser les filtres"><i class="fa-solid fa-xmark"></i></a>
            @endif
        </div>
    </form>

    <div class="overflow-x-auto rounded-lg bg-white border border-ink-faint/30">
        <table class="min-w-full text-sm">
            <thead class="bg-paper text-left text-ink-soft">
                <tr>
                    <th class="px-4 py-3 font-medium">Titre</th>
                    <th class="px-4 py-3 font-medium">Auteur</th>
                    <th class="px-4 py-3 font-medium">ISBN</th>
                    <th class="px-4 py-3 font-medium">Catégorie</th>
                    <th class="px-4 py-3 font-medium">Année</th>
                    <th class="px-4 py-3 font-medium">Stock</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-faint/20">
                @forelse ($livres as $livre)
                    <tr class="hover:bg-paper/60">
                        <td class="px-4 py-3 font-medium">{{ $livre->titre }}</td>
                        <td class="px-4 py-3">{{ $livre->auteur }}</td>
                        <td class="px-4 py-3 text-ink-soft">{{ $livre->isbn }}</td>
                        <td class="px-4 py-3">{{ $livre->categorie }}</td>
                        <td class="px-4 py-3">{{ $livre->annee }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if ($livre->quantite_disponible > 0)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-light px-2.5 py-0.5 text-xs font-medium text-brand-dark">
                                    <i class="fa-solid fa-check"></i> {{ $livre->quantite_disponible }} / {{ $livre->quantite_totale }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-late-light px-2.5 py-0.5 text-xs font-medium text-late">
                                    <i class="fa-solid fa-ban"></i> Épuisé · 0 / {{ $livre->quantite_totale }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('livres.edit', $livre) }}" title="Modifier"
                                   class="rounded p-2 text-ink-soft hover:bg-brand-light hover:text-brand-dark">
                                    <i class="fa-solid fa-pen-to-square"></i><span class="sr-only">Modifier</span>
                                </a>
                                <form method="POST" action="{{ route('livres.destroy', $livre) }}"
                                      onsubmit="return confirm('Supprimer le livre « {{ addslashes($livre->titre) }} » ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Supprimer" class="rounded p-2 text-ink-soft hover:bg-late-light hover:text-late">
                                        <i class="fa-solid fa-trash"></i><span class="sr-only">Supprimer</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-ink-soft">
                            <i class="fa-solid fa-book-open text-3xl text-ink-faint mb-3"></i>
                            <p>Aucun livre ne correspond à votre recherche.</p>
                            <a href="{{ route('livres.create') }}" class="mt-2 inline-block text-brand hover:underline">Ajouter un livre</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination (F10) --}}
    <div class="mt-6">{{ $livres->links() }}</div>
@endsection
