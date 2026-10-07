<?php

use App\Http\Controllers\AdherentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\EmpruntController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LivreController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
	Route::get('/connexion', [AuthenticatedSessionController::class, 'create'])->name('login');
	Route::get('/inscription', [AuthenticatedSessionController::class, 'register'])->name('register');
	Route::post('/connexion', [AuthenticatedSessionController::class, 'store']);
	Route::post('/inscription', [AuthenticatedSessionController::class, 'registerPost'])->name('registerPost');
});

Route::middleware('auth')->group(function (): void {
	Route::post('/deconnexion', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
	Route::get('/', [HomeController::class, 'index'])->name('dashboard');

	Route::resource('livres', LivreController::class)->except(['show']);
	Route::resource('adherents', AdherentController::class);

	Route::get('/emprunts', [EmpruntController::class, 'index'])->name('emprunts.index');
	Route::get('/emprunts/creer', [EmpruntController::class, 'create'])->name('emprunts.create');
	Route::post('/emprunts', [EmpruntController::class, 'store'])->name('emprunts.store');
	Route::get('/emprunts/export', [EmpruntController::class, 'export'])->name('emprunts.export');
	Route::patch('/emprunts/{emprunt}/retour', [EmpruntController::class, 'retour'])->name('emprunts.retour');
});