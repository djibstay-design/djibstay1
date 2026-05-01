<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('demande_partenaires', function (Blueprint $table) {
            if (!Schema::hasColumn('demande_partenaires', 'token_invitation')) {
                $table->string('token_invitation')->nullable()->unique()->after('formulaire_rempli');
            }
            if (!Schema::hasColumn('demande_partenaires', 'invitation_envoyee_le')) {
                $table->timestamp('invitation_envoyee_le')->nullable()->after('token_invitation');
            }
            if (!Schema::hasColumn('demande_partenaires', 'token_expire_le')) {
                $table->timestamp('token_expire_le')->nullable()->after('invitation_envoyee_le');
            }
            if (!Schema::hasColumn('demande_partenaires', 'traite_par')) {
                $table->unsignedBigInteger('traite_par')->nullable()->after('token_expire_le');
            }
            if (!Schema::hasColumn('demande_partenaires', 'notes_admin')) {
                $table->text('notes_admin')->nullable()->after('traite_par');
            }
        });
    }

    public function down(): void {
        Schema::table('demande_partenaires', function (Blueprint $table) {
            $table->dropColumn([
                'token_invitation',
                'invitation_envoyee_le',
                'token_expire_le',
                'traite_par',
                'notes_admin',
            ]);
        });
    }
};