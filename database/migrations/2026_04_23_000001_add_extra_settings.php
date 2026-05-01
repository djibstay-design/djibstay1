<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            // Localisation
            ['key' => 'app_devise',        'value' => 'DJF'],
            ['key' => 'app_langue',        'value' => 'fr'],
            ['key' => 'app_timezone',      'value' => 'Africa/Djibouti'],
            ['key' => 'app_date_format',   'value' => 'DD/MM/YYYY'],

            // Sécurité
            ['key' => 'maintenance_mode',    'value' => '0'],
            ['key' => 'maintenance_message', 'value' => 'Le site est en maintenance. Revenez bientôt !'],
            ['key' => 'inscription_active',  'value' => '1'],
            ['key' => 'max_resa_client',     'value' => '5'],

            // Notifications
            ['key' => 'notif_nouvelle_resa', 'value' => '1'],
            ['key' => 'notif_annulation',    'value' => '1'],
            ['key' => 'notif_avis',          'value' => '1'],

            // Politique hôtel
            ['key' => 'hotel_age_minimum',   'value' => '18'],
            ['key' => 'hotel_animaux',       'value' => '0'],

            // Équipe support — nombre de membres
            ['key' => 'support_team_count',  'value' => '2'],

            // Support membre 1
            ['key' => 'support_1_nom',       'value' => 'Mohamed Ali Hassan'],
            ['key' => 'support_1_poste',     'value' => 'Responsable Support'],
            ['key' => 'support_1_email',     'value' => 'support1@djibstay.dj'],
            ['key' => 'support_1_telephone', 'value' => '+253 77 00 00 01'],
            ['key' => 'support_1_whatsapp',  'value' => '+253 77 00 00 01'],
            ['key' => 'support_1_disponible','value' => '1'],

            // Support membre 2
            ['key' => 'support_2_nom',       'value' => 'Fatima Omar Abdallah'],
            ['key' => 'support_2_poste',     'value' => 'Assistante Clientèle'],
            ['key' => 'support_2_email',     'value' => 'support2@djibstay.dj'],
            ['key' => 'support_2_telephone', 'value' => '+253 77 00 00 02'],
            ['key' => 'support_2_whatsapp',  'value' => '+253 77 00 00 02'],
            ['key' => 'support_2_disponible','value' => '1'],

            // Support membre 3 (vide par défaut)
            ['key' => 'support_3_nom',       'value' => ''],
            ['key' => 'support_3_poste',     'value' => ''],
            ['key' => 'support_3_email',     'value' => ''],
            ['key' => 'support_3_telephone', 'value' => ''],
            ['key' => 'support_3_whatsapp',  'value' => ''],
            ['key' => 'support_3_disponible','value' => '0'],

            // Support membre 4 (vide par défaut)
            ['key' => 'support_4_nom',       'value' => ''],
            ['key' => 'support_4_poste',     'value' => ''],
            ['key' => 'support_4_email',     'value' => ''],
            ['key' => 'support_4_telephone', 'value' => ''],
            ['key' => 'support_4_whatsapp',  'value' => ''],
            ['key' => 'support_4_disponible','value' => '0'],

            // Support membre 5 (vide par défaut)
            ['key' => 'support_5_nom',       'value' => ''],
            ['key' => 'support_5_poste',     'value' => ''],
            ['key' => 'support_5_email',     'value' => ''],
            ['key' => 'support_5_telephone', 'value' => ''],
            ['key' => 'support_5_whatsapp',  'value' => ''],
            ['key' => 'support_5_disponible','value' => '0'],
        ];

        foreach ($settings as $s) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $s['key']],
                ['value' => $s['value']]
            );
        }
    }

    public function down(): void {}
};