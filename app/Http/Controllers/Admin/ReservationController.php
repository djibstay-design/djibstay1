<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReservationStatusChangedMail;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
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

    public function show(Request $request, Reservation $reservation): View|RedirectResponse
    {
        $this->authorizeReservation($request, $reservation);
        $reservation->load(['chambre.typeChambre.hotel']);
        return view('admin.reservations.show', compact('reservation'));
    }

    public function edit(Request $request, Reservation $reservation): View|RedirectResponse
    {
        $this->authorizeReservation($request, $reservation);
        return view('admin.reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $this->authorizeReservation($request, $reservation);

        $validated = $request->validate([
            'statut' => ['required', 'in:EN_ATTENTE,CONFIRMEE,ANNULEE'],
        ]);

        $ancienStatut = $reservation->statut;
        $reservation->update($validated);

        if ($ancienStatut !== $reservation->statut) {
            Mail::to($reservation->email_client)->send(new ReservationStatusChangedMail($reservation, $ancienStatut));
        }

        return redirect()->route('admin.reservations.show', $reservation)->with('success', 'Statut mis à jour.');
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
}
