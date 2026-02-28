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
            'chambre_id' => ['required', 'exists:chambres,id'],
            'nom_client' => ['required', 'string', 'max:100'],
            'prenom_client' => ['required', 'string', 'max:100'],
            'email_client' => ['required', 'email'],
            'telephone_client' => ['nullable', 'string', 'max:20'],
            'code_identite' => ['required', 'string', 'max:50'],
            'date_debut' => ['required', 'date', 'after_or_equal:today'],
            'date_fin' => ['required', 'date', 'after:date_debut'],
            'quantite' => ['required', 'integer', 'min:1'],
            'photos' => ['nullable', 'string'],
        ]);

        $conflicting = Reservation::where('chambre_id', $chambre->id)
            ->whereIn('statut', ['EN_ATTENTE', 'CONFIRMEE'])
            ->where('date_debut', '<', $validated['date_fin'])
            ->where('date_fin', '>', $validated['date_debut'])
            ->exists();

        if ($conflicting) {
            return back()->withInput()->withErrors(['date_debut' => 'Cette chambre n\'est pas disponible pour les dates choisies.']);
        }

        $prixUnitaire = $chambre->typeChambre->prix_par_nuit;
        $nuits = (strtotime($validated['date_fin']) - strtotime($validated['date_debut'])) / 86400;
        $montantTotal = round($prixUnitaire * $validated['quantite'] * $nuits, 2);

        $reservation = Reservation::create([
            ...$validated,
            'date_reservation' => now()->toDateString(),
            'prix_unitaire' => $prixUnitaire,
            'montant_total' => $montantTotal,
            'statut' => 'EN_ATTENTE',
            'code_reservation' => 'RES-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
        ]);

        return redirect()->route('reservations.confirmation', $reservation)
            ->with('success', 'Réservation envoyée. Conservez votre code de réservation.');
    }

    public function confirmation(Reservation $reservation): View
    {
        $reservation->load('chambre.typeChambre.hotel');
        return view('reservations.confirmation', compact('reservation'));
    }
}
