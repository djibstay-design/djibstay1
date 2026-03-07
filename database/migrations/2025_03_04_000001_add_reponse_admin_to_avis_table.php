<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            $table->text('reponse_admin')->nullable()->after('commentaire');
            $table->timestamp('reponse_admin_at')->nullable()->after('reponse_admin');
            $table->foreignId('reponse_admin_user_id')->nullable()->after('reponse_admin_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            $table->dropForeign(['reponse_admin_user_id']);
            $table->dropColumn(['reponse_admin', 'reponse_admin_at', 'reponse_admin_user_id']);
        });
    }
};
