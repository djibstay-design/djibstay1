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
            $query->whereHas('chambre.typeChambre.hotel', fn ($q) => $q->where('user_id', $user->id));
        }
        $reservations = $query->latest('date_reservation')->paginate(15);
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
        if ($request->user()->role !== 'SUPER_ADMIN' && $reservation->chambre->typeChambre->hotel->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
