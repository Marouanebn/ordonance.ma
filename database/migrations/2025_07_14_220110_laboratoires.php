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
        Schema::create('laboratoires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nom_responsable')->nullable();
            $table->string('nom_laboratoire')->nullable();
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('numero_autorisation')->unique()->nullable();
            $table->enum('statut', ['actif', 'inactif'])->nullable();
            $table->string('piece_identite_recto')->nullable();
            $table->string('piece_identite_verso')->nullable();
            $table->string('diplome')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratoires');
    }
};
