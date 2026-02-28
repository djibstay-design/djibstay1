<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_chambre', function (Blueprint $table) {
            $table->id();
            $table->string('nom_type', 100);
            $table->integer('capacite');
            $table->text('description')->nullable();
            $table->decimal('prix_par_nuit', 10, 2);
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_chambre');
    }
};
