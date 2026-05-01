<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    private function getHotel()
    {
        $user = auth()->user();
        return Hotel::where(fn($q) => $q->where('user_id',$user->id)->orWhere('admin_id',$user->id))->firstOrFail();
    }

    public function index(Request $request)
    {
        $hotel = $this->getHotel();
        $query = Reservation::with('chambre.typeChambre')
            ->whereHas('chambre.typeChambre', fn($q) => $q->where('hotel_id',$hotel->id));

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nom_client','like',"%$s%")->orWhere('code_reservation','like',"%$s%")->orWhere('email_client','like',"%$s%"));
        }

        $reservations = $query->latest()->paginate(15)->withQueryString();
        $stats = [
            'total'      => Reservation::whereHas('chambre.typeChambre', fn($q) => $q->where('hotel_id',$hotel->id))->count(),
            'confirmee'  => Reservation::whereHas('chambre.typeChambre', fn($q) => $q->where('hotel_id',$hotel->id))->where('statut','CONFIRMEE')->count(),
            'en_attente' => Reservation::whereHas('chambre.typeChambre', fn($q) => $q->where('hotel_id',$hotel->id))->where('statut','EN_ATTENTE')->count(),
            'annulee'    => Reservation::whereHas('chambre.typeChambre', fn($q) => $q->where('hotel_id',$hotel->id))->where('statut','ANNULEE')->count(),
        ];
        return view('hotel_admin.reservations.index', compact('hotel','reservations','stats'));
    }

    public function show(Reservation $reservation)
    {
        $hotel = $this->getHotel();
        abort_if($reservation->chambre->typeChambre->hotel_id !== $hotel->id, 403);
        $reservation->load(['chambre.typeChambre','payments']);
        return view('hotel_admin.reservations.show', compact('hotel','reservation'));
    }

    public function updateStatut(Request $request, Reservation $reservation)
    {
        $hotel = $this->getHotel();
        abort_if($reservation->chambre->typeChambre->hotel_id !== $hotel->id, 403);
        $request->validate(['statut' => ['required','in:CONFIRMEE,ANNULEE,EN_ATTENTE']]);
        $ancienStatut = $reservation->statut;
        $reservation->update(['statut' => $request->statut]);

        if ($ancienStatut !== $reservation->statut) {
            try {
                // Email au Client
                if ($reservation->email_client) {
                    \Mail::to($reservation->email_client)->send(
                        new \App\Mail\ReservationStatusChangedMail($reservation, $ancienStatut)
                    );
                }
                // Email au Propriétaire (Lui-même ou admin)
                if ($hotel->user && $hotel->user->email) {
                    \Mail::to($hotel->user->email)->send(
                        new \App\Mail\ReservationStatusChangedMail($reservation, $ancienStatut)
                    );
                }

                // Notification Push Firebase (Si client lié à un compte mobile)
                $client = $reservation->user;
                if ($client && $client->fcm_token) {
                    $title = "Mise à jour de votre réservation";
                    $body = "L'hôtel {$hotel->nom} a passé votre réservation {$reservation->code_reservation} à : {$reservation->statut}.";
                    \App\Services\FirebaseService::sendNotification($client->fcm_token, $title, $body, [
                        'reservation_id' => $reservation->id,
                        'new_status' => $reservation->statut
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::error('Erreur notifications HotelAdmin: '.$e->getMessage());
            }
        }

        return back()->with('success','Statut mis à jour.');
    }
}