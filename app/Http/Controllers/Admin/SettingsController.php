<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
   private array $keys = [
    // Identité
    'app_name','app_slogan','app_logo','app_favicon',
    // Coordonnées
    'contact_adresse','contact_ville','contact_telephone','contact_email','contact_whatsapp',
    // Emails
    'mail_from_address','mail_from_name','mail_contact_receiver','mail_resa_receiver',
    // Support général
    'support_horaires','support_disponible',
    // Support membres
    'support_team_count',
    'support_1_nom','support_1_poste','support_1_email','support_1_telephone','support_1_whatsapp','support_1_disponible',
    'support_2_nom','support_2_poste','support_2_email','support_2_telephone','support_2_whatsapp','support_2_disponible',
    'support_3_nom','support_3_poste','support_3_email','support_3_telephone','support_3_whatsapp','support_3_disponible',
    'support_4_nom','support_4_poste','support_4_email','support_4_telephone','support_4_whatsapp','support_4_disponible',
    'support_5_nom','support_5_poste','support_5_email','support_5_telephone','support_5_whatsapp','support_5_disponible',
    // Pages
    'about_text','footer_copyright','social_facebook','social_instagram','social_twitter',
    // Réservation
    'resa_acompte_percent','resa_annulation_heures','resa_conditions',
    // Paiements Mobile
    'payment_waafi_merchant','payment_dmoney_merchant',
    // Localisation
    'app_devise','app_langue','app_timezone','app_date_format',
    // Sécurité
    'maintenance_mode','maintenance_message','inscription_active','max_resa_client',
    // Notifications
    'notif_nouvelle_resa','notif_annulation','notif_avis',
    // Politique
    'hotel_age_minimum','hotel_animaux',
];

    public function edit()
    {
        $settings = [];
        foreach ($this->keys as $key) {
            $settings[$key] = SiteSetting::get($key, '');
        }
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name'              => ['required','string','max:100'],
            'contact_email'         => ['nullable','email'],
            'mail_from_address'     => ['nullable','email'],
            'mail_contact_receiver' => ['nullable','email'],
            'mail_resa_receiver'    => ['nullable','email'],
            'support_email'         => ['nullable','email'],
            'resa_acompte_percent'  => ['nullable','integer','min:1','max:100'],
            'resa_annulation_heures'=> ['nullable','integer','min:0'],
        ]);

        // Logo upload
        if ($request->hasFile('app_logo_file')) {
            $old = SiteSetting::get('app_logo');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('app_logo_file')->store('settings', 'public');
            SiteSetting::set('app_logo', $path);
        }

        // Favicon upload
        if ($request->hasFile('app_favicon_file')) {
            $old = SiteSetting::get('app_favicon');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('app_favicon_file')->store('settings', 'public');
            SiteSetting::set('app_favicon', $path);
        }

        // Tous les autres champs
        $textKeys = array_diff($this->keys, ['app_logo','app_favicon']);
        foreach ($textKeys as $key) {
            if ($request->has($key)) {
                SiteSetting::set($key, $request->input($key) ?? '');
            }
        }

        return back()->with('success', 'Paramètres enregistrés avec succès.');
    }
}