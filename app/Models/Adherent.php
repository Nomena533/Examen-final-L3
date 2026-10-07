<?php

namespace App\Models;

use App\Models\Emprunt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Adherent extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'date_inscription',
    ];

    protected function casts(): array
    {
        return [
            'date_inscription' => 'date',
        ];
    }

    public function emprunts(): HasMany
    {
        return $this->hasMany(Emprunt::class);
    }
}