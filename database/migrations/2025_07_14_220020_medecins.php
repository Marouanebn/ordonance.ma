<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('medecins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nom_complet');
            $table->string('telephone');
            $table->string('numero_cnom')->unique();
            $table->string('specialite');
            $table->string('adresse_cabinet');
            $table->string('ville');
            $table->string('photo_profil')->nullable();
            $table->string('piece_identite_recto')->nullable();
            $table->string('piece_identite_verso')->nullable();
            $table->string('diplome')->nullable();
            $table->string('attestation_cnom')->nullable();
            $table->enum('statut', ['actif', 'inactif']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medecins');
    }
};
