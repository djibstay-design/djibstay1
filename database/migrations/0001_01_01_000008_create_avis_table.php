<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->string('nom_client', 100);
            $table->string('email_client', 150);
            $table->integer('note');
            $table->text('commentaire')->nullable();
            $table->date('date_avis');
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
