<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReservationStatusChangedMail;
use App\Models\Chambre;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
{
    // ✅ Au chargement : on annule dans la DB les EN_ATTENTE expirées
    Reservation::where('statut', 'EN_ATTENTE')
        ->where('date_debut', '<', now()->toDateString())
        ->update(['statut' => 'ANNULEE']);

    $user = $request->user();
    $query = Reservation::with(['chambre.typeChambre.hotel']);
    if ($user->role !== 'SUPER_ADMIN') {
        $query->whereHas('chambre.typeChambre.hotel', fn ($q) => $q->where('user_id', $user->id)->orWhere('admin_id', $user->id));
    }
    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(function ($qry) use ($q) {
            $qry->where('code_reservation', 'like', "%{$q}%")
                ->orWhere('nom_client', 'like', "%{$q}%")
                ->orWhere('prenom_client', 'like', "%{$q}%");
        });
    }
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }
    $reservations = $query->latest('date_reservation')->paginate(15)->withQueryString();

    return view('admin.reservations.index', compact('reservations'));
}

    /**
     * Réservation sur place (client à l’hôtel) — sans passage par le paiement en ligne.
     */
    public function create(Request $request): View
    {
        $user = $request->user();
        $chambresQuery = Chambre::query()
            ->with(['typeChambre.hotel'])
            ->where('etat', 'DISPONIBLE')
            ->whereHas('typeChambre.hotel', function ($hq) use ($user) {
                if ($user->role !== 'SUPER_ADMIN') {
                    $hq->where(function ($h) use ($user) {
                        $h->where('user_id', $user->id)->orWhere('admin_id', $user->id);
                    });
                }
            });

        $chambres = $chambresQuery->get()->sortBy(function (Chambre $c) {
            $hotel = $c->typeChambre->hotel;

            return ($hotel->nom ?? '').'|'.($c->numero ?? '');
        })->values();

        return view('admin.reservations.create', compact('chambres'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'chambre_id' => ['required', 'exists:chambres,id'],
            'nom_client' => ['required', 'string', 'max:100'],
            'prenom_client' => ['required', 'string', 'max:100'],
            'email_client' => ['nullable', 'email', 'max:150'],
            'telephone_client' => ['nullable', 'string', 'max:20'],
            'code_identite' => ['nullable', 'string', 'max:50'],
            'date_debut' => ['required', 'date', 'after_or_equal:today'],
            'date_fin' => ['required', 'date', 'after:date_debut'],
            'quantite' => ['required', 'integer', 'min:1'],
            'photo_carte' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'photo_visage' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ]);

        $chambre = Chambre::with('typeChambre.hotel')->findOrFail($validated['chambre_id']);
        $this->authorizeChambreForAdmin($request, $chambre);

        $conflicting = Reservation::where('chambre_id', $chambre->id)
            ->whereIn('statut', ['EN_ATTENTE', 'CONFIRMEE'])
            ->where('date_debut', '<', $validated['date_fin'])
            ->where('date_fin', '>', $validated['date_debut'])
            ->exists();

        if ($conflicting) {
            return back()->withInput()->withErrors(['date_debut' => 'Cette chambre n’est pas disponible pour les dates choisies.']);
        }

        $prixUnitaire = $chambre->typeChambre->prix_par_nuit;
        $nuits = (strtotime($validated['date_fin']) - strtotime($validated['date_debut'])) / 86400;
        $montantTotal = round($prixUnitaire * $validated['quantite'] * $nuits, 2);

        $email = $validated['email_client'] ?? null;
        if ($email === null || $email === '') {
            $email = 'sur-place-'.Str::lower(Str::random(10)).'@djibstay.local';
        }

        $reservation = Reservation::create([
            'chambre_id' => $validated['chambre_id'],
            'nom_client' => $validated['nom_client'],
            'prenom_client' => $validated['prenom_client'],
            'email_client' => $email,
            'telephone_client' => $validated['telephone_client'] ?? null,
            'code_identite' => ! empty($validated['code_identite'])
                ? $validated['code_identite']
                : 'Sur place',
            'date_reservation' => now()->toDateString(),
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'],
            'quantite' => $validated['quantite'],
            'prix_unitaire' => $prixUnitaire,
            'montant_total' => $montantTotal,
            'statut' => 'CONFIRMEE',
            'code_reservation' => 'RES-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
        ]);

        $basePath = 'reservations/'.$reservation->id;
        if ($request->hasFile('photo_carte')) {
            $reservation->update([
                'photo_carte' => $request->file('photo_carte')->store($basePath, 'public'),
            ]);
        }
        if ($request->hasFile('photo_visage')) {
            $reservation->update([
                'photo_visage' => $request->file('photo_visage')->store($basePath, 'public'),
            ]);
        }

        return redirect()
            ->route('admin.reservations.show', $reservation)
            ->with('success', 'Réservation sur place enregistrée et confirmée.');
    }

    public function show(Request $request, Reservation $reservation): View|RedirectResponse
    {
        $this->authorizeReservation($request, $reservation);
        $reservation->load(['chambre.typeChambre.hotel']);

        return view('admin.reservations.show', compact('reservation'));
    }

   public function edit(Reservation $reservation): View
{
    $chambres = \App\Models\Chambre::with(['typeChambre.hotel'])
        ->orderBy('numero')
        ->get();

    return view('admin.reservations.edit', compact('reservation', 'chambres'));
}

    public function update(Request $request, Reservation $reservation)
{
    $validated = $request->validate([
        'nom_client'     => ['required','string','max:100'],
        'prenom_client'  => ['required','string','max:100'],
        'email_client'   => ['required','email'],
        'telephone_client'=> ['nullable','string','max:30'],
        'code_identite'  => ['nullable','string','max:100'],
        'chambre_id'     => ['required','exists:chambres,id'],
        'statut'         => ['required','in:EN_ATTENTE,CONFIRMEE,ANNULEE'],
        'date_debut'     => ['required','date'],
        'date_fin'       => ['required','date','after:date_debut'],
        'quantite'       => ['nullable','integer','min:1'],
        'montant_total'  => ['nullable','numeric','min:0'],
    ]);

    $ancienStatut = $reservation->statut;
    $reservation->update($validated);

    // Envoi d'email si le statut a changé
    if ($ancienStatut !== $reservation->statut) {
        try {
            // Email au Client
            if ($reservation->email_client) {
                \Mail::to($reservation->email_client)->send(
                    new \App\Mail\ReservationStatusChangedMail($reservation, $ancienStatut)
                );
            }

            // Email au Propriétaire de l'hôtel (Partenaire) et au Gestionnaire
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

            // Notification Push Firebase (Si client lié à un compte mobile)
            $client = $reservation->user;
            if ($client && $client->fcm_token) {
                $title = "Mise à jour de votre réservation";
                $body = "Le statut de votre réservation {$reservation->code_reservation} est passé à : {$reservation->statut}.";
                \App\Services\FirebaseService::sendNotification($client->fcm_token, $title, $body, [
                    'reservation_id' => $reservation->id,
                    'new_status' => $reservation->statut
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error('Erreur envoi notifications statut admin: ' . $e->getMessage());
        }
    }

    return redirect()->route('admin.reservations.show', $reservation)
        ->with('success', 'Réservation mise à jour.');
}

    public function destroy(Request $request, Reservation $reservation): RedirectResponse
    {
        $this->authorizeReservation($request, $reservation);
        $reservation->delete();

        return redirect()->route('admin.reservations.index')->with('success', 'Réservation supprimée.');
    }

    private function authorizeReservation(Request $request, Reservation $reservation): void
    {
        $userId = $request->user()->id;
        $hotel = $reservation->chambre->typeChambre->hotel;
        if ($request->user()->role !== 'SUPER_ADMIN' && $hotel->user_id !== $userId && $hotel->admin_id !== $userId) {
            abort(403);
        }
    }

    private function authorizeChambreForAdmin(Request $request, Chambre $chambre): void
    {
        $chambre->loadMissing('typeChambre.hotel');
        $userId = $request->user()->id;
        $hotel = $chambre->typeChambre->hotel;
        if ($request->user()->role !== 'SUPER_ADMIN' && $hotel->user_id !== $userId && $hotel->admin_id !== $userId) {
            abort(403);
        }
    }
}
