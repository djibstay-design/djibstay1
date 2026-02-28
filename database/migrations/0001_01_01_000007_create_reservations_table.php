<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('nom_client', 100);
            $table->string('prenom_client', 100);
            $table->string('email_client', 150);
            $table->string('telephone_client', 20)->nullable();
            $table->string('code_identite', 50);
            $table->foreignId('chambre_id')->constrained('chambres')->cascadeOnDelete();
            $table->date('date_reservation');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 10, 2)->nullable();
            $table->text('photos')->nullable();
            $table->enum('statut', ['EN_ATTENTE', 'CONFIRMEE', 'ANNULEE'])->default('EN_ATTENTE');
            $table->string('code_reservation', 100)->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
