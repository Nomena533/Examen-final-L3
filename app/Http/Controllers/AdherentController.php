<?php

namespace App\Http\Controllers;

use App\Models\Adherent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdherentController extends Controller
{
    public function index(): View
    {
        $adherents = Adherent::query()
            ->withCount(['emprunts as emprunts_en_cours_count' => fn ($query) => $query->whereNull('date_retour_effective')])
            ->orderBy('nom')
            ->orderBy('prenom')
            ->paginate(15);

        return view('adherents.index', compact('adherents'));
    }

    public function create(): View
    {
        return view('adherents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:adherents,email'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'date_inscription' => ['required', 'date'],
        ]);

        $adherent = Adherent::create($data);

        return redirect()->route('adherents.show', $adherent)->with('success', 'Adhérent inscrit.');
    }

    public function show(Adherent $adherent): View
    {
        $adherent->load(['emprunts' => fn ($query) => $query->with('livre')->latest('date_emprunt')]);

        return view('adherents.show', compact('adherent'));
    }

    public function edit(Adherent $adherent): View
    {
        return view('adherents.edit', compact('adherent'));
    }

    public function update(Request $request, Adherent $adherent): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('adherents', 'email')->ignore($adherent->id)],
            'telephone' => ['nullable', 'string', 'max:30'],
            'date_inscription' => ['required', 'date'],
        ]);

        $adherent->update($data);

        return redirect()->route('adherents.show', $adherent)->with('success', 'Adhérent modifié.');
    }

    public function destroy(Adherent $adherent): RedirectResponse
    {
        if ($adherent->emprunts()->exists()) {
            return back()->with('error', 'Cet adhérent ne peut pas être supprimé car il possède un historique d’emprunts.');
        }

        $adherent->delete();

        return redirect()->route('adherents.index')->with('success', 'Adhérent supprimé.');
    }
}