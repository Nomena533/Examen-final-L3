<?php

namespace App\Http\Controllers;

use App\Models\Adherent;
use App\Models\Emprunt;
use App\Models\Livre;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $retards = Emprunt::query()
            ->with(['livre', 'adherent'])
            ->whereNull('date_retour_effective')
            ->whereDate('date_retour_prevue', '<', today())
            ->orderBy('date_retour_prevue')
            ->get();

        return view('dashboard', [
            'totalLivres' => Livre::count(),
            'totalAdherents' => Adherent::count(),
            'empruntsEnCours' => Emprunt::whereNull('date_retour_effective')->count(),
            'nbRetards' => $retards->count(),
            'retards' => $retards,
        ]);
    }
}
