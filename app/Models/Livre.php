<?php

namespace App\Models;

use App\Models\Emprunt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livre extends Model
{
    protected $fillable = [
        'titre',
        'auteur',
        'isbn',
        'categorie',
        'annee',
        'quantite_totale',
        'quantite_disponible',
    ];

    protected function casts(): array
    {
        return [
            'annee' => 'integer',
            'quantite_totale' => 'integer',
            'quantite_disponible' => 'integer',
        ];
    }

    public function emprunts(): HasMany
    {
        return $this->hasMany(Emprunt::class);
    }
}