<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LivreController extends Controller
{
    public function index(Request $request): View
    {
        $query = Livre::query()->orderBy('titre');

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(fn ($builder) => $builder
                ->where('titre', 'like', "%{$search}%")
                ->orWhere('auteur', 'like', "%{$search}%"));
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->string('categorie')->toString());
        }

        if ($request->boolean('disponibles')) {
            $query->where('quantite_disponible', '>', 0);
        }

        return view('livres.index', [
            'livres' => $query->paginate(10)->withQueryString(),
            'categories' => Livre::query()->select('categorie')->distinct()->orderBy('categorie')->pluck('categorie'),
        ]);
    }

    public function create(): View
    {
        return view('livres.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'auteur' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:20', 'unique:livres,isbn'],
            'categorie' => ['required', 'string', 'max:255'],
            'annee' => ['required', 'integer', 'min:0', 'max:32767'],
            'quantite_totale' => ['required', 'integer', 'min:0'],
        ]);
        $data['quantite_disponible'] = $data['quantite_totale'];

        Livre::create($data);

        return redirect()->route('livres.index')->with('success', 'Livre ajouté au catalogue.');
    }

    public function edit(Livre $livre): View
    {
        return view('livres.edit', compact('livre'));
    }

    public function update(Request $request, Livre $livre): RedirectResponse
    {
        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'auteur' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:20', Rule::unique('livres', 'isbn')->ignore($livre->id)],
            'categorie' => ['required', 'string', 'max:255'],
            'annee' => ['required', 'integer', 'min:0', 'max:32767'],
            'quantite_totale' => ['required', 'integer', 'min:0'],
        ]);

        $empruntes = $livre->quantite_totale - $livre->quantite_disponible;
        if ($data['quantite_totale'] < $empruntes) {
            return back()->withErrors([
                'quantite_totale' => "Le total doit être au moins égal aux {$empruntes} exemplaires empruntés.",
            ])->withInput();
        }

        $data['quantite_disponible'] = $data['quantite_totale'] - $empruntes;
        $livre->update($data);

        return redirect()->route('livres.index')->with('success', 'Livre modifié.');
    }

    public function destroy(Livre $livre): RedirectResponse
    {
        if ($livre->emprunts()->exists()) {
            return back()->with('error', 'Ce livre ne peut pas être supprimé car il possède un historique d’emprunts.');
        }

        $livre->delete();

        return redirect()->route('livres.index')->with('success', 'Livre supprimé.');
    }
}