{{-- Variables : $adherent (nullable en création) --}}
@php $adherent = $adherent ?? null; @endphp

<div class="grid gap-5 sm:grid-cols-2">
    @include('partials.input', ['name' => 'nom', 'label' => 'Nom', 'required' => true, 'value' => $adherent?->nom])
    @include('partials.input', ['name' => 'prenom', 'label' => 'Prénom', 'required' => true, 'value' => $adherent?->prenom])
    @include('partials.input', ['name' => 'email', 'label' => 'Adresse e-mail', 'type' => 'email', 'required' => true,
             'value' => $adherent?->email, 'placeholder' => 'prenom.nom@exemple.mg', 'hint' => 'Doit être unique.'])
    @include('partials.input', ['name' => 'telephone', 'label' => 'Téléphone', 'type' => 'tel',
             'value' => $adherent?->telephone, 'placeholder' => '034 00 000 00'])
    <div class="sm:col-span-2 sm:max-w-xs">
        @include('partials.input', [
            'name' => 'date_inscription', 'label' => 'Date d\'inscription', 'type' => 'date', 'required' => true,
            'value' => $adherent?->date_inscription?->format('Y-m-d') ?? now()->format('Y-m-d'),
        ])
    </div>
</div>
