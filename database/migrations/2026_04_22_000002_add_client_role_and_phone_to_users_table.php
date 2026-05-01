<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modifier l'enum pour ajouter CLIENT et phone
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('SUPER_ADMIN', 'ADMIN', 'CLIENT') DEFAULT 'CLIENT'");

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('SUPER_ADMIN', 'ADMIN') DEFAULT 'ADMIN'");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};