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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medecin_id')->nullable()->constrained('medecins')->nullOnDelete();
            $table->string('nom_complet');
            $table->string('cin')->unique();
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->date('date_naissance');
            $table->enum('genre', ['homme', 'femme']);
            $table->string('numero_securite_sociale')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
