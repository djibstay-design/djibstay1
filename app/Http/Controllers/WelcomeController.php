<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\TypeChambre;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class WelcomeController extends Controller
{
    public function __invoke(Request $request): View|Response
    {
        try {
        $query = Hotel::query()
            ->with(['typesChambre', 'avis'])
            ->withAvg('avis', 'note');

        $roomsWanted = max(1, min(20, (int) $request->input('rooms', 1)));
        $adults = max(1, min(30, (int) $request->input('adults', 2)));
        $children = max(0, min(20, (int) $request->input('children', 0)));
        $guestsTotal = $adults + $children;
        /** Capacité minimale par chambre pour accueillir le groupe (répartition équitable sur les chambres demandées) */
        $minCapacityPerRoom = max(1, (int) ceil($guestsTotal / $roomsWanted));
        /** Filtre « personnes par chambre » : valeurs issues des types_chambre.capacite */
        if ($request->filled('min_capacity')) {
            $filterCap = max(1, min(50, (int) $request->input('min_capacity')));
            $minCapacityPerRoom = max($minCapacityPerRoom, $filterCap);
        }
        $stayFilterSummary = null;
        $stayAvailabilityApplied = false;

        // Disponibilité : N chambres libres sur la période + type assez grand pour le nombre de voyageurs
        if ($request->filled('check_in') && $request->filled('check_out')) {
            try {
                $checkIn = Carbon::parse($request->input('check_in'))->startOfDay();
                $checkOut = Carbon::parse($request->input('check_out'))->startOfDay();
                if ($checkOut->gt($checkIn)) {
                    $query->withRoomAvailableBetween(
                        $checkIn->toDateString(),
                        $checkOut->toDateString(),
                        $roomsWanted,
                        $minCapacityPerRoom
                    );
                    $stayAvailabilityApplied = true;
                    $stayFilterSummary = [
                        'check_in' => $checkIn->copy()->locale('fr'),
                        'check_out' => $checkOut->copy()->locale('fr'),
                        'rooms' => $roomsWanted,
                        'adults' => $adults,
                        'children' => $children,
                        'guests_total' => $guestsTotal,
                        'min_room_capacity' => $minCapacityPerRoom,
                        'nights' => $checkIn->diffInDays($checkOut),
                    ];
                }
            } catch (\Throwable) {
                // dates invalides ignorées
            }
        }

        // Filter by city (valeur exacte depuis la liste des villes en base)
        if ($request->filled('city')) {
            $query->where('ville', $request->input('city'));
        }

        // Sans recherche par dates : filtrer les hôtels proposant au moins un type ≥ capacité choisie
        if ($request->filled('min_capacity') && ! $stayAvailabilityApplied) {
            $capFilter = max(1, min(50, (int) $request->input('min_capacity')));
            $query->whereHas('typesChambre', fn ($q) => $q->where('capacite', '>=', $capFilter));
        }

        // Filter by room type name (hôtels proposant au moins ce type de chambre)
        if ($request->filled('room_type')) {
            $typeName = (string) $request->input('room_type');
            $query->whereHas('typesChambre', fn ($q) => $q->where('nom_type', $typeName));
        }

        $query->orderBy('nom');

        $hotels = $query->paginate(12)->withQueryString();

        $stats = [
            'total_hotels' => Hotel::count(),
            'total_bookings' => Reservation::count(),
            'avg_rating' => (float) Hotel::withAvg('avis', 'note')->get()->avg(fn ($h) => $h->avis_avg_note) ?: 4.8,
        ];

        // Map hotel name (or part of) to image file in public/images — order matters (first match wins)
        $hotelImageMap = [
            'sheraton' => 'sheraton.jpeg',
            'kempinski' => 'kempinski.jpeg',
            'ayla' => 'ayla.jpg',
            'les sables' => 'ayla.jpg',
            'sables blancs' => 'ayla.jpg',
            'escale' => 'escale.jpg',
            'waafi' => 'waafi.jpg',
            'waaf' => 'waafi.jpg',
            'best western' => 'best western.jpeg',
            'atlantic' => 'best western.jpeg',
            'europe' => 'hotel europe.jpg',
            'gadileh' => 'gadileh.jpg',
            'accacia' => 'accacia-hotel.jpg',
            'acacias' => 'accacia-hotel.jpg',
        ];

        // Fallback list: each hotel without a name match gets a different image (by id)
        $hotelImagesFallback = ['ayla.jpg', 'kempinski.jpeg', 'sheraton.jpeg', 'escale.jpg', 'waafi.jpg', 'gadileh.jpg', 'hotel europe.jpg', 'best western.jpeg'];

        $roomTypes = TypeChambre::query()
            ->select('nom_type')
            ->distinct()
            ->orderBy('nom_type')
            ->pluck('nom_type');

        $cities = Hotel::query()
            ->whereNotNull('ville')
            ->where('ville', '!=', '')
            ->distinct()
            ->orderBy('ville')
            ->pluck('ville')
            ->values();

        $roomCapacities = TypeChambre::query()
            ->select('capacite')
            ->distinct()
            ->orderBy('capacite')
            ->pluck('capacite')
            ->values();

        return view('welcome', compact(
            'hotels',
            'stats',
            'hotelImageMap',
            'hotelImagesFallback',
            'stayFilterSummary',
            'roomTypes',
            'cities',
            'roomCapacities'
        ));
        } catch (\Throwable $e) {
            Log::error('WelcomeController error', [
                'message'   => $e->getMessage(),
                'exception' => get_class($e),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return response(
                implode("\n", [
                    '=== WelcomeController Exception ===',
                    'Type    : ' . get_class($e),
                    'Message : ' . $e->getMessage(),
                    'File    : ' . $e->getFile() . ':' . $e->getLine(),
                    '',
                    '--- Stack Trace ---',
                    $e->getTraceAsString(),
                ]),
                500,
                ['Content-Type' => 'text/plain']
            );
        }
    }
}
