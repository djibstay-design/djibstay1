<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = Hotel::query()
            ->with(['typesChambre', 'avis'])
            ->withAvg('avis', 'note');

        // Filter by city
        if ($request->filled('city')) {
            $query->where('ville', 'like', '%' . $request->city . '%');
        }

        // Filter by min price (via typesChambre)
        if ($request->filled('min_price')) {
            $query->whereHas('typesChambre', fn ($q) => $q->where('prix_par_nuit', '>=', $request->min_price));
        }

        // Filter by max price
        if ($request->filled('max_price')) {
            $query->whereHas('typesChambre', fn ($q) => $q->where('prix_par_nuit', '<=', $request->max_price));
        }

        // Filter by min rating
        if ($request->filled('min_rating')) {
            $minRating = (float) $request->min_rating;
            $query->having('avis_avg_note', '>=', $minRating);
        }

        // Sort
        match ($request->get('sort', 'rating')) {
            'price_asc' => $query->orderByRaw('(SELECT MIN(prix_par_nuit) FROM types_chambre WHERE types_chambre.hotel_id = hotels.id) ASC'),
            'price_desc' => $query->orderByRaw('(SELECT MAX(prix_par_nuit) FROM types_chambre WHERE types_chambre.hotel_id = hotels.id) DESC'),
            default => $query->orderByDesc('avis_avg_note'),
        };

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

        return view('welcome', compact('hotels', 'stats', 'hotelImageMap', 'hotelImagesFallback'));
    }
}
