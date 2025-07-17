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
        Schema::create('pharmaciens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nom_pharmacie');
            $table->string('telephone')->nullable();
            $table->string('adresse_pharmacie')->nullable();
            $table->string('ville')->nullable();
            $table->enum('statut', ['actif', 'inactif'])->nullable();
            $table->string('document_justificatif_url')->nullable();
            $table->string('piece_identite_recto')->nullable();
            $table->string('piece_identite_verso')->nullable();
            $table->string('diplome')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmaciens');
    }
};
