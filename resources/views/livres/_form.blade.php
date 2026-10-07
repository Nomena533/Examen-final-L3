{{-- Variables : $livre (nullable en création) --}}
@php $livre = $livre ?? null; @endphp

<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        @include('partials.input', [
            'name' => 'titre',
            'label' => 'Titre',
            'required' => true,
            'value' => $livre?->titre,
        ])
    </div>
    @include('partials.input', [
        'name' => 'auteur',
        'label' => 'Auteur',
        'required' => true,
        'value' => $livre?->auteur,
    ])
    @include('partials.input', [
        'name' => 'isbn',
        'label' => 'ISBN',
        'required' => true,
        'value' => $livre?->isbn,
        'placeholder' => '978-2-07-036822-8',
        'hint' => 'Doit être unique dans le catalogue.',
    ])
    @include('partials.input', [
        'name' => 'categorie',
        'label' => 'Catégorie',
        'required' => true,
        'value' => $livre?->categorie,
        'placeholder' => 'Roman, Science, Histoire…',
    ])
    @include('partials.input', [
        'name' => 'annee',
        'label' => 'Année de publication',
        'type' => 'number',
        'required' => true,
        'value' => $livre?->annee,
        'min' => 0,
    ])

    @php
        $empruntes = $livre ? $livre->quantite_totale - $livre->quantite_disponible : 0;
    @endphp
    <div class="sm:col-span-2">
        @include('partials.input', [
            'name' => 'quantite_totale',
            'label' => 'Nombre d\'exemplaires',
            'type' => 'number',
            'required' => true,
            'value' => $livre?->quantite_totale,
            'min' => $empruntes,
            'hint' => $livre
                ? "Au moins $empruntes (exemplaires actuellement empruntés). Le stock disponible est recalculé automatiquement."
                : 'Le stock disponible sera égal à ce nombre à la création.',
        ])
    </div>
</div>
