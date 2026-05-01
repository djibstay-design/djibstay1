<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Insérer tous les paramètres par défaut
        $settings = [
            // Identité
            ['key' => 'app_name',       'value' => 'DjibStay'],
            ['key' => 'app_slogan',     'value' => 'Réservez les meilleurs hôtels à Djibouti'],
            ['key' => 'app_logo',       'value' => ''],
            ['key' => 'app_favicon',    'value' => ''],

            // Coordonnées
            ['key' => 'contact_adresse',   'value' => 'Plateau du Serpent, Djibouti-Ville'],
            ['key' => 'contact_ville',     'value' => 'Djibouti-Ville'],
            ['key' => 'contact_telephone', 'value' => '+253 77 00 00 00'],
            ['key' => 'contact_email',     'value' => 'contact@djibstay.dj'],
            ['key' => 'contact_whatsapp',  'value' => '+253 77 00 00 00'],

            // Emails
            ['key' => 'mail_from_address',     'value' => 'contact@djibstay.dj'],
            ['key' => 'mail_from_name',        'value' => 'DjibStay'],
            ['key' => 'mail_contact_receiver', 'value' => 'contact@djibstay.dj'],
            ['key' => 'mail_resa_receiver',    'value' => 'reservations@djibstay.dj'],

            // Support client
            ['key' => 'support_nom',          'value' => 'Mohamed Ali Hassan'],
            ['key' => 'support_email',        'value' => 'support@djibstay.dj'],
            ['key' => 'support_telephone',    'value' => '+253 77 00 00 01'],
            ['key' => 'support_whatsapp',     'value' => '+253 77 00 00 01'],
            ['key' => 'support_horaires',     'value' => 'Lun–Ven : 8h00 – 17h00 | Sam : 9h00 – 13h00'],
            ['key' => 'support_disponible',   'value' => '1'],

            // Pages publiques
            ['key' => 'about_text',        'value' => 'DjibStay est né d\'un constat simple : réserver un hôtel à Djibouti était trop compliqué. Aujourd\'hui, nous connectons les voyageurs avec les meilleurs hôtels du pays en quelques clics.'],
            ['key' => 'footer_copyright',  'value' => '© 2026 DjibStay — Tous droits réservés'],
            ['key' => 'social_facebook',   'value' => 'https://facebook.com/djibstay'],
            ['key' => 'social_instagram',  'value' => 'https://instagram.com/djibstay'],
            ['key' => 'social_twitter',    'value' => 'https://twitter.com/djibstay'],

            // Réservation
            ['key' => 'resa_acompte_percent',    'value' => '30'],
            ['key' => 'resa_annulation_heures',  'value' => '48'],
            ['key' => 'resa_conditions',         'value' => 'L\'acompte est non remboursable en cas d\'annulation moins de 48h avant l\'arrivée. Le solde sera réglé directement à l\'hôtel. Une pièce d\'identité valide sera demandée à l\'arrivée.'],
        ];

        foreach ($settings as $setting) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }

    public function down(): void {}
};