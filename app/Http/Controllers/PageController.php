<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\DemandePartenaire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:120'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'name.required'    => 'Indiquez votre nom.',
            'email.required'   => 'Indiquez une adresse e-mail valide.',
            'message.required' => 'Écrivez votre message.',
        ]);

        ContactMessage::create([
            'nom'       => $validated['name'],
            'email'     => $validated['email'],
            'telephone' => $validated['phone'] ?? null,
            'sujet'     => $validated['subject'] ?? null,
            'message'   => $validated['message'],
            'source'    => 'formulaire',
            'lu'        => false,
        ]);

        Log::channel('single')->info('Contact DjibStay', [
            'ip' => $request->ip(),
            ...$validated,
        ]);

        return redirect()
            ->route('pages.contact')
            ->with('success', 'Merci ! Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
    }

    public function partenaireSubmit(Request $request): RedirectResponse
    {
        $request->validate([
            'nom_contact'     => ['required', 'string', 'max:120'],
            'email_contact'   => ['required', 'email', 'max:255'],
            'telephone'       => ['nullable', 'string', 'max:40'],
            'nom_hotel'       => ['required', 'string', 'max:150'],
            'ville'           => ['nullable', 'string', 'max:100'],
            'nombre_chambres' => ['nullable', 'integer', 'min:1'],
            'message'         => ['nullable', 'string', 'max:2000'],
            'accepte_conditions' => ['accepted'],
        ], [
            'nom_contact.required'     => 'Indiquez votre nom.',
            'email_contact.required'   => 'Indiquez votre email.',
            'nom_hotel.required'       => 'Indiquez le nom de votre hôtel.',
            'accepte_conditions.accepted' => 'Vous devez accepter les conditions de partenariat.',
        ]);

        DemandePartenaire::create([
            'nom_contact'     => $request->nom_contact,
            'email_contact'   => $request->email_contact,
            'telephone'       => $request->telephone,
            'nom_hotel'       => $request->nom_hotel,
            'ville'           => $request->ville,
            'nombre_chambres' => $request->nombre_chambres,
            'message'         => $request->message,
            'statut'          => 'en_attente',
        ]);

        return redirect()
            ->route('pages.contact')
            ->with('success_partenaire', 'Votre demande de partenariat a bien été envoyée ! Notre équipe vous contactera sous 48h.');
    }
}