<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emprunts', function (Blueprint $table) {
            $table->id();

            // restrictOnDelete : on ne supprime pas un livre/adhérent référencé par un emprunt
            // (la règle « pas de suppression si emprunts en cours » est vérifiée dans le contrôleur)
            $table->foreignId('livre_id')->constrained('livres')->restrictOnDelete();
            $table->foreignId('adherent_id')->constrained('adherents')->restrictOnDelete();

            $table->date('date_emprunt');
            $table->date('date_retour_prevue');
            $table->date('date_retour_effective')->nullable();
            $table->timestamps();

            // accélère « emprunts en cours » et « retards »
            $table->index(['date_retour_effective', 'date_retour_prevue']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emprunts');
    }
};
