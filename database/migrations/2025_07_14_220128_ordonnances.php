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
        Schema::create('ordonnances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medecin_id')->constrained()->cascadeOnDelete();
            $table->date('date_prescription');
            $table->string('status')->default('active'); // active, validated, rejected, dispensed
            $table->text('detail')->nullable();
            $table->foreignId('validated_by_pharmacie_id')->nullable()->constrained('pharmaciens')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('medicament_ordonnance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordonnance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicament_id')->constrained()->cascadeOnDelete();
            $table->integer('quantite')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicament_ordonnance');
        Schema::dropIfExists('ordonnances');
    }
};
