<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('types_chambre', function (Blueprint $table) {
            $table->unsignedSmallInteger('superficie_m2')->nullable()->after('description');
            $table->string('lit_description', 255)->nullable()->after('superficie_m2');
            $table->boolean('has_climatisation')->default(false)->after('lit_description');
            $table->boolean('has_minibar')->default(false)->after('has_climatisation');
            $table->boolean('has_wifi')->default(true)->after('has_minibar');
            $table->text('equipements_salle_bain')->nullable()->after('has_wifi');
            $table->text('equipements_generaux')->nullable()->after('equipements_salle_bain');
        });
    }

    public function down(): void
    {
        Schema::table('types_chambre', function (Blueprint $table) {
            $table->dropColumn([
                'superficie_m2',
                'lit_description',
                'has_climatisation',
                'has_minibar',
                'has_wifi',
                'equipements_salle_bain',
                'equipements_generaux',
            ]);
        });
    }
};
