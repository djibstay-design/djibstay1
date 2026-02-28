<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chambres', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20);
            $table->enum('etat', ['DISPONIBLE', 'OCCUPEE', 'MAINTENANCE'])->default('DISPONIBLE');
            $table->foreignId('type_id')->constrained('types_chambre')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chambres');
    }
};
