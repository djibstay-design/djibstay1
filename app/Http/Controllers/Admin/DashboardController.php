<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $hotels = $user->role === 'SUPER_ADMIN'
            ? Hotel::withCount(['typesChambre', 'avis'])->get()
            : Hotel::where('user_id', $user->id)->withCount(['typesChambre', 'avis'])->get();

        $reservationsQuery = Reservation::query();
        if ($user->role !== 'SUPER_ADMIN') {
            $reservationsQuery->whereHas('chambre.typeChambre.hotel', fn ($q) => $q->where('user_id', $user->id));
        }
        $reservationsCount = $reservationsQuery->count();

        return view('admin.dashboard', [
            'hotels' => $hotels,
            'reservationsCount' => $reservationsCount,
        ]);
    }
}
