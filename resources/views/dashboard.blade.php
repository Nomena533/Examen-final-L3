{{--
    Variables attendues :
      $totalLivres, $totalAdherents, $empruntsEnCours, $nbRetards : int
      $retards : Collection d'Emprunt en retard, avec ->with(['livre', 'adherent'])
--}}
@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')
    <h1 class="font-serif text-3xl font-bold mb-6">Tableau de bord</h1>

    @php
        $stats = [
            ['label' => 'Livres au catalogue',    'value' => $totalLivres,      'icon' => 'fa-book',                 'route' => 'livres.index',    'alert' => false],
            ['label' => 'Adhérents inscrits',     'value' => $totalAdherents,   'icon' => 'fa-users',                'route' => 'adherents.index', 'alert' => false],
            ['label' => 'Emprunts en cours',      'value' => $empruntsEnCours,  'icon' => 'fa-right-left',           'route' => 'emprunts.index',  'alert' => false],
            ['label' => 'Emprunts en retard',     'value' => $nbRetards,        'icon' => 'fa-triangle-exclamation', 'route' => 'emprunts.index',  'alert' => $nbRetards > 0],
        ];
    @endphp

    {{-- <div class="bg-red-500">Bonjour</div> --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 rounded-lg bg-white border border-ink-faint/30 divide-x divide-y lg:divide-y-0 divide-ink-faint/20 overflow-hidden">
        @foreach ($stats as $stat)
            <a href="{{ route($stat['route']) }}"
               class="group p-6 hover:bg-paper/70 {{ $stat['alert'] ? 'bg-late-light/60' : '' }}">
                <div class="flex items-center gap-2 text-sm {{ $stat['alert'] ? 'text-late' : 'text-ink-soft' }}">
                    <i class="fa-solid {{ $stat['icon'] }}"></i> {{ $stat['label'] }}
                </div>
                <p class="mt-3 font-serif text-5xl font-bold {{ $stat['alert'] ? 'text-late' : 'text-ink' }}">{{ $stat['value'] }}</p>
            </a>
        @endforeach
    </div>

    <section class="mt-10">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-serif text-xl font-bold">Retards à relancer</h2>
            <a href="{{ route('emprunts.index') }}" class="text-sm text-brand hover:underline">Tous les emprunts en cours</a>
        </div>

        @if ($retards->isEmpty())
            <div class="flex items-center gap-3 rounded-lg bg-white border border-ink-faint/30 px-5 py-6 text-sm text-ink-soft">
                <i class="fa-solid fa-circle-check text-2xl text-brand"></i>
                Aucun retard pour le moment : tous les livres empruntés sont dans les temps.
            </div>
        @else
            <div class="overflow-x-auto rounded-lg bg-white border border-ink-faint/30">
                <table class="min-w-full text-sm">
                    <thead class="bg-paper text-left text-ink-soft">
                        <tr>
                            <th class="px-4 py-3 font-medium">Livre</th>
                            <th class="px-4 py-3 font-medium">Adhérent</th>
                            <th class="px-4 py-3 font-medium">Retour prévu</th>
                            <th class="px-4 py-3 font-medium">Retard</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-faint/20">
                        @foreach ($retards as $emprunt)
                            <tr class="bg-late-light/50">
                                <td class="px-4 py-3 font-medium">{{ $emprunt->livre->titre }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('adherents.show', $emprunt->adherent) }}" class="hover:text-brand hover:underline">
                                        {{ $emprunt->adherent->prenom }} {{ $emprunt->adherent->nom }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $emprunt->date_retour_prevue->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-semibold text-late">
                                    <i class="fa-solid fa-clock mr-1"></i>{{ (int) $emprunt->date_retour_prevue->diffInDays(today()) }} j
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
