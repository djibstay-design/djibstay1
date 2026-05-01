<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('demande_partenaires', function (Blueprint $table) {
            $table->id();
            $table->string('nom_contact');
            $table->string('email_contact');
            $table->string('telephone')->nullable();
            $table->string('nom_hotel');
            $table->string('ville')->nullable();
            $table->text('description')->nullable();
            $table->integer('nombre_chambres')->nullable();
            $table->string('site_web')->nullable();
            $table->text('message')->nullable();
            $table->enum('statut', ['en_attente', 'en_discussion', 'valide', 'refuse'])
                  ->default('en_attente');
                  $table->string('token_invitation')->nullable()->unique(); // lien unique généré par le système
$table->timestamp('invitation_envoyee_le')->nullable();
$table->timestamp('token_expire_le')->nullable();
$table->foreignId('traite_par')->nullable()->constrained('users'); // quel admin a traité
$table->text('notes_admin')->nullable(); // notes internes admin
            $table->string('lien_formulaire')->nullable(); // lien Google Form envoyé
            $table->boolean('formulaire_rempli')->default(false);
            $table->timestamp('valide_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('demande_partenaires');
    }
};