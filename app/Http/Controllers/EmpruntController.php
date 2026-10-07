<?php

namespace App\Http\Controllers;

use App\Models\Adherent;
use App\Models\Emprunt;
use App\Models\Livre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmpruntController extends Controller
{
    public function index(): View
    {
        $emprunts = Emprunt::query()
            ->with(['livre', 'adherent'])
            ->whereNull('date_retour_effective')
            ->orderBy('date_retour_prevue')
            ->get();

        return view('emprunts.index', compact('emprunts'));
    }

    public function create(): View
    {
        return view('emprunts.create', [
            'livres' => Livre::query()->where('quantite_disponible', '>', 0)->orderBy('titre')->get(),
            'adherents' => Adherent::query()->orderBy('nom')->orderBy('prenom')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'livre_id' => ['required', 'integer', 'exists:livres,id'],
            'adherent_id' => ['required', 'integer', 'exists:adherents,id'],
            'date_emprunt' => ['required', 'date'],
            'date_retour_prevue' => ['required', 'date', 'after_or_equal:date_emprunt'],
        ]);

        $error = DB::transaction(function () use ($data): ?string {
            $livre = Livre::query()->lockForUpdate()->findOrFail($data['livre_id']);
            $adherent = Adherent::query()->lockForUpdate()->findOrFail($data['adherent_id']);

            if ($livre->quantite_disponible < 1) {
                return 'Aucun exemplaire de ce livre n’est disponible.';
            }

            $empruntsEnCours = $adherent->emprunts()->whereNull('date_retour_effective');
            if ($empruntsEnCours->count() >= 3) {
                return 'Cet adhérent a déjà atteint la limite de trois emprunts en cours.';
            }

            if ($empruntsEnCours->whereDate('date_retour_prevue', '<', today())->exists()) {
                return 'Cet adhérent ne peut pas emprunter tant qu’un retard n’a pas été rendu.';
            }

            $adherent->emprunts()->create($data);
            $livre->decrement('quantite_disponible');

            return null;
        });

        if ($error !== null) {
            return back()->withErrors(['livre_id' => $error])->withInput();
        }

        return redirect()->route('emprunts.index')->with('success', 'Emprunt enregistré.');
    }

    public function retour(Emprunt $emprunt): RedirectResponse
    {
        DB::transaction(function () use ($emprunt): void {
            $emprunt = Emprunt::query()->lockForUpdate()->findOrFail($emprunt->id);

            if ($emprunt->date_retour_effective !== null) {
                return;
            }

            $emprunt->update(['date_retour_effective' => today()]);
            Livre::query()->whereKey($emprunt->livre_id)->increment('quantite_disponible');
        });

        return redirect()->route('emprunts.index')->with('success', 'Retour enregistré.');
    }

    public function export(): StreamedResponse
    {
        $emprunts = Emprunt::query()
            ->with(['livre', 'adherent'])
            ->whereNull('date_retour_effective')
            ->orderBy('date_retour_prevue')
            ->get();

        return response()->streamDownload(function () use ($emprunts): void {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Livre', 'Adhérent', 'E-mail', 'Date emprunt', 'Retour prévu', 'Retard (jours)'], ';');

            foreach ($emprunts as $emprunt) {
                fputcsv($file, [
                    $emprunt->livre->titre,
                    $emprunt->adherent->prenom.' '.$emprunt->adherent->nom,
                    $emprunt->adherent->email,
                    $emprunt->date_emprunt->format('d/m/Y'),
                    $emprunt->date_retour_prevue->format('d/m/Y'),
                    $emprunt->date_retour_prevue->lt(today()) ? $emprunt->date_retour_prevue->diffInDays(today()) : 0,
                ], ';');
            }

            fclose($file);
        }, 'emprunts-en-cours.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}