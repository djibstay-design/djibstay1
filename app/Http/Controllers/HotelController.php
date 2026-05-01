<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function index(Request $request): View
    {
        $query = Hotel::query()
            ->with(['typesChambre', 'avis'])
            ->withAvg('avis', 'note');

        $roomsWanted = max(1, min(20, (int) $request->input('rooms', 1)));
        $adults = max(1, min(30, (int) $request->input('adults', 2)));
        $children = max(0, min(20, (int) $request->input('children', 0)));
        $guestsTotal = $adults + $children;
        $minCapacityPerRoom = max(1, (int) ceil($guestsTotal / $roomsWanted));

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
                }
            } catch (\Throwable) {
            }
        }

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('city')) {
            $query->where('ville', 'like', '%'.$request->city.'%');
        }

        if ($request->filled('min_price')) {
            $query->whereHas('typesChambre', fn ($q) => $q->where('prix_par_nuit', '>=', $request->min_price));
        }

        if ($request->filled('max_price')) {
            $query->whereHas('typesChambre', fn ($q) => $q->where('prix_par_nuit', '<=', $request->max_price));
        }

        if ($request->filled('min_rating')) {
            $minRating = (float) $request->min_rating;
            $query->having('avis_avg_note', '>=', $minRating);
        }

        match ($request->get('sort', 'recommended')) {
            'price_asc' => $query->orderByRaw('(SELECT MIN(prix_par_nuit) FROM types_chambre WHERE types_chambre.hotel_id = hotels.id) ASC'),
            'price_desc' => $query->orderByRaw('(SELECT MAX(prix_par_nuit) FROM types_chambre WHERE types_chambre.hotel_id = hotels.id) DESC'),
            'rating' => $query->orderByDesc('avis_avg_note'),
            default => $query->orderByDesc('avis_avg_note'),
        };

        $hotels = $query->paginate(12)->withQueryString();

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

        $hotelImagesFallback = ['ayla.jpg', 'kempinski.jpeg', 'sheraton.jpeg', 'escale.jpg', 'waafi.jpg', 'gadileh.jpg', 'hotel europe.jpg', 'best western.jpeg'];

        return view('hotels.index', compact('hotels', 'hotelImageMap', 'hotelImagesFallback'));
    }

    public function show(Hotel $hotel): View
    {
        $hotel->load([
            'images',
            'mainImage',
            'typesChambre' => fn ($q) => $q->with([
                'images',
                'chambres' => fn ($c) => $c->where('etat', 'DISPONIBLE'),
            ]),
            'avis',
        ]);

        return view('hotels.show', compact('hotel'));
    }
}
