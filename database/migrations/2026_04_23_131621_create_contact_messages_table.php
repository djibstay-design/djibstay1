<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->string('sujet')->nullable();
            $table->text('message');
            $table->text('reponse')->nullable();
            $table->boolean('lu')->default(false);
            $table->boolean('archive')->default(false);
            $table->enum('source', ['formulaire', 'gmail'])->default('formulaire');
            $table->string('gmail_uid')->nullable(); // ID unique Gmail
            $table->timestamp('repondu_le')->nullable();
            $table->foreignId('repondu_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('contact_messages');
    }
};