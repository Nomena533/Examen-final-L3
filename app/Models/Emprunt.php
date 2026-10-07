<?php

namespace App\Models;

use App\Models\Adherent;
use App\Models\Livre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emprunt extends Model
{
    protected $fillable = [
        'livre_id',
        'adherent_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour_effective',
    ];

    protected function casts(): array
    {
        return [
            'date_emprunt' => 'date',
            'date_retour_prevue' => 'date',
            'date_retour_effective' => 'date',
        ];
    }

    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class);
    }

    public function adherent(): BelongsTo
    {
        return $this->belongsTo(Adherent::class);
    }
}