<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationStatusController extends Controller
{
    public function show(Request $request): View
    {
        $code = $request->query('code');
        $reservation = null;

        if ($code) {
            $reservation = Reservation::with('chambre.typeChambre.hotel')
                ->where('code_reservation', $code)
                ->first();
        }

        return view('reservations.status', [
            'reservation' => $reservation,
            'code' => $code,
        ]);
    }
}
