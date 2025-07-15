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
        Schema::create('demande_laboratoires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacien_id')->constrained()->cascadeOnDelete();
            $table->foreignId('laboratoire_id')->constrained()->cascadeOnDelete();
            $table->date('date_demande');
            $table->string('statut');
            $table->timestamps();
        });

        Schema::create('demande_laboratoire_medicament', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_laboratoire_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicament_id')->constrained()->cascadeOnDelete();
            $table->integer('quantite')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_laboratoire_medicament');
        Schema::dropIfExists('demande_laboratoires');
    }
};
