<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'payment_kind')) {
                $table->string('payment_kind', 20)->default('acompte')->after('reservation_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('payments') || ! Schema::hasColumn('payments', 'payment_kind')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_kind');
        });
    }
};
