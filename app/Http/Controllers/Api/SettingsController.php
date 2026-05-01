<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    /**
     * Retourne la configuration globale du site pour l'application mobile.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'app_name'              => SiteSetting::get('app_name', 'DjibStay'),
            'app_logo'              => SiteSetting::get('app_logo') ? url('storage/' . SiteSetting::get('app_logo')) : null,
            'app_devise'            => SiteSetting::get('app_devise', 'DJF'),
            'resa_acompte_percent'  => (int) SiteSetting::get('resa_acompte_percent', 30),
            'resa_annulation_heures'=> (int) SiteSetting::get('resa_annulation_heures', 24),
            'inscription_active'    => (bool) SiteSetting::get('inscription_active', true),
            'contact_tel'           => SiteSetting::get('contact_tel'),
            'contact_whatsapp'      => SiteSetting::get('contact_whatsapp'),
            'payment_waafi_merchant' => SiteSetting::get('payment_waafi_merchant'),
            'payment_dmoney_merchant' => SiteSetting::get('payment_dmoney_merchant'),
            'social_links' => [
                'facebook'  => SiteSetting::get('facebook_url'),
                'instagram' => SiteSetting::get('instagram_url'),
                'twitter'   => SiteSetting::get('twitter_url'),
            ]
        ]);
    }

    /**
     * Retourne les méthodes de paiement actives et leurs instructions.
     */
    public function paymentMethods(): JsonResponse
    {
        $methods = PaymentMethod::where('is_active', true)->get()->map(function($m) {
            return [
                'id'           => $m->id,
                'name'         => $m->name,
                'type'         => $m->type, // e-wallet, card, bank_transfer
                'instructions' => $m->instructions,
                'icon'         => $m->icon,
            ];
        });

        return response()->json($methods);
    }
}
