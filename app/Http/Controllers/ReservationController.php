<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function create(Request $request): View
    {
        $chambre = Chambre::with('typeChambre.hotel')->findOrFail($request->query('chambre_id'));

        return view('reservations.create', compact('chambre'));
    }

    public function store(Request $request): RedirectResponse
    {
        $chambre = Chambre::with('typeChambre')->findOrFail($request->chambre_id);

        $validated = $request->validate([
            'chambre_id'       => ['required', 'exists:chambres,id'],
            'nom_client'       => ['required', 'string', 'max:100'],
            'prenom_client'    => ['required', 'string', 'max:100'],
            'email_client'     => ['required', 'email'],
            'telephone_client' => ['nullable', 'string', 'max:20'],
            'code_identite'    => ['required', 'string', 'max:50'],
            'date_debut'       => ['required', 'date', 'after_or_equal:today'],
            'date_fin'         => ['required', 'date', 'after:date_debut'],
            'quantite'         => ['required', 'integer', 'min:1'],
            'photo_carte'      => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'photo_visage'     => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ]);

        $disponible = $chambre->typeChambre->calculerDisponibilite($validated['date_debut'], $validated['date_fin']);

        if ($validated['quantite'] > $disponible) {
            return back()->withInput()->withErrors([
                'date_debut' => $disponible > 0 
                    ? "Désolé, il ne reste que $disponible chambre(s) de ce type pour les dates choisies."
                    : "Désolé, ce type de chambre est complet pour les dates choisies."
            ]);
        }

        $prixUnitaire = $chambre->typeChambre->prix_par_nuit;
        $nuits        = (strtotime($validated['date_fin']) - strtotime($validated['date_debut'])) / 86400;
        $montantTotal = round($prixUnitaire * $validated['quantite'] * $nuits, 2);

        $reservation = Reservation::create([
            'chambre_id'       => $validated['chambre_id'],
            'nom_client'       => $validated['nom_client'],
            'prenom_client'    => $validated['prenom_client'],
            'email_client'     => $validated['email_client'],
            'telephone_client' => $validated['telephone_client'] ?? null,
            'code_identite'    => $validated['code_identite'],
            'date_reservation' => now()->toDateString(),
            'date_debut'       => $validated['date_debut'],
            'date_fin'         => $validated['date_fin'],
            'quantite'         => $validated['quantite'],
            'prix_unitaire'    => $prixUnitaire,
            'montant_total'    => $montantTotal,
            'statut'           => 'EN_ATTENTE',
            'code_reservation' => 'RES-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
        ]);

        $basePath    = 'reservations/'.$reservation->id;
        $photoCarte  = $request->file('photo_carte')->store($basePath, 'public');
        $photoVisage = $request->file('photo_visage')->store($basePath, 'public');

        $reservation->update([
            'photo_carte'  => $photoCarte,
            'photo_visage' => $photoVisage,
        ]);

        return redirect()
            ->route('reservations.payment.show', $reservation)
            ->with('success', 'Demande enregistrée. Réglez l\'acompte ('.Reservation::DEPOSIT_PERCENT.'%) pour finaliser.');
    }

    public function confirmation(Reservation $reservation): View|RedirectResponse
    {
        if (!$reservation->hasPaidDeposit()) {
            return redirect()
                ->route('reservations.payment.show', $reservation)
                ->withErrors([
                    'payment' => 'Vous devez d\'abord régler l\'acompte de '.Reservation::DEPOSIT_PERCENT.'% pour afficher la confirmation.'
                ]);
        }

        $reservation->load([
            'chambre.typeChambre.hotel',
            'payments' => fn($q) => $q->where('payment_kind', \App\Models\Payment::KIND_DEPOSIT)->latest()
        ]);

        return view('reservations.confirmation', compact('reservation'));
    }

    public function annuler(Request $request, Reservation $reservation): RedirectResponse
{
    if ($reservation->statut === 'ANNULEE') {
        return back()->with('error', 'Cette réservation est déjà annulée.');
    }

    $reservation->update(['statut' => 'ANNULEE']);

    try {
        $ancienStatut = 'EN_ATTENTE'; // Ou récupérer l'état actuel avant l'update
        $adminHotel = $reservation->chambre->typeChambre->hotel->user;
        
        // Email au Client
        if ($reservation->email_client) {
            \Mail::to($reservation->email_client)->send(
                new \App\Mail\ReservationStatusChangedMail($reservation, $ancienStatut)
            );
        }

        // Email au Propriétaire et au Gestionnaire
        $hotel = $reservation->chambre->typeChambre->hotel;
        $recipients = [];
        if ($hotel->user && $hotel->user->email) $recipients[] = $hotel->user->email;
        if ($hotel->admin_id && $hotel->admin_id != $hotel->user_id) {
            if ($hotel->admin && $hotel->admin->email) $recipients[] = $hotel->admin->email;
        }
        $recipients = array_unique($recipients);

        if (!empty($recipients)) {
            $mail = \Mail::to($recipients);
            $globalReceiver = \App\Models\SiteSetting::get('mail_resa_receiver');
            if ($globalReceiver && !in_array($globalReceiver, $recipients)) {
                $mail->bcc($globalReceiver);
            }
            $mail->send(new \App\Mail\ReservationStatusChangedMail($reservation, $ancienStatut));
        }
    } catch (\Throwable $e) {
        \Log::error('Email annulation non envoyé : '.$e->getMessage());
    }

    // Rediriger selon la page d'origine
    $referer = $request->headers->get('referer', '');
    if (str_contains($referer, 'statut')) {
        return redirect()
            ->route('reservations.status', ['code' => $reservation->code_reservation])
            ->with('success', 'Votre réservation a été annulée.');
    }

    return redirect()
        ->route('reservations.confirmation', $reservation)
        ->with('success', 'Votre réservation a été annulée.');
}
}