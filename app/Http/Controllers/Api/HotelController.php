<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\TypeChambre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Hotel::query()
            ->with(['typesChambre', 'avis', 'images', 'mainImage'])
            ->withAvg('avis', 'note');

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('city')) {
            $query->where('ville', 'like', '%'.$request->city.'%');
        }

        // Filtrage par disponibilité (Dates)
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $in = $request->check_in;
            $out = $request->check_out;

            $query->whereHas('typesChambre', function($q) use ($in, $out) {
                $q->whereHas('chambres', function($cq) use ($in, $out) {
                    $cq->where('etat', 'DISPONIBLE')
                       ->whereDoesntHave('reservations', function($rq) use ($in, $out) {
                           $rq->whereIn('statut', ['EN_ATTENTE', 'CONFIRMEE'])
                              ->where('date_debut', '<', $out)
                              ->where('date_fin', '>', $in);
                       });
                });
            });
        }

        if ($request->get('sort') === 'price_asc') {
            $query->orderBy(function($q) {
                return \App\Models\TypeChambre::whereColumn('hotel_id', 'hotels.id')->selectRaw('min(prix_par_nuit)');
            });
        } elseif ($request->get('sort') === 'rating_desc') {
            $query->orderByDesc('avis_avg_note');
        } else {
            $query->latest();
        }

        $hotels = $query->get();

        $data = $hotels->map(function (Hotel $hotel) {
            return $this->formatHotel($hotel);
        });

        return response()->json(['data' => $data]);
    }

    public function featured(): JsonResponse
    {
        $hotels = Hotel::query()
            ->with(['typesChambre', 'avis', 'images', 'mainImage'])
            ->withAvg('avis', 'note')
            ->orderByDesc('avis_avg_note')
            ->take(6)
            ->get();

        $data = $hotels->map(function (Hotel $hotel) {
            return $this->formatHotel($hotel);
        });

        return response()->json(['data' => $data]);
    }

    public function show(Hotel $hotel): JsonResponse
    {
        $hotel->load(['typesChambre.chambres', 'typesChambre.images', 'avis', 'images', 'mainImage']);
        $hotel->loadAvg('avis', 'note');

        return response()->json(['data' => $this->formatHotel($hotel, full: true)]);
    }

    public function rooms(Hotel $hotel): JsonResponse
    {
        $hotel->load(['typesChambre.chambres', 'typesChambre.images']);

        $data = $hotel->typesChambre->map(function (TypeChambre $type) use ($hotel) {
            $availableCount = $type->chambres->where('etat', 'DISPONIBLE')->count();

            return [
                'id' => $type->id,
                'hotel_id' => $hotel->id,
                'name' => $type->nom_type,
                'description' => $type->description,
                'type' => $type->nom_type,
                'price_per_night' => (float) $type->prix_par_nuit,
                'capacity' => $type->capacite,
                'size' => null,
                'thumbnail' => $type->images->first()?->url ? url('storage/'.$type->images->first()->url) : null,
                'images' => $type->images->map(fn($i) => url('storage/'.$i->url))->toArray(),
                'amenities' => [],
                'is_available' => $availableCount > 0,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function room(int $roomId): JsonResponse
    {
        $type = TypeChambre::with(['hotel', 'chambres', 'images'])->findOrFail($roomId);
        $availableCount = $type->chambres->where('etat', 'DISPONIBLE')->count();

        return response()->json([
            'data' => [
                'id' => $type->id,
                'hotel_id' => $type->hotel_id,
                'name' => $type->nom_type,
                'description' => $type->description,
                'type' => $type->nom_type,
                'price_per_night' => (float) $type->prix_par_nuit,
                'capacity' => $type->capacite,
                'size' => null,
                'thumbnail' => $type->images->first()?->url,
                'images' => $type->images->pluck('url')->filter()->values()->toArray(),
                'amenities' => [],
                'is_available' => $availableCount > 0,
            ],
        ]);
    }

    private function formatHotel(Hotel $hotel, bool $full = false): array
    {
        $minPrice = $hotel->typesChambre->min('prix_par_nuit');
        $rating = round((float) ($hotel->avis_avg_note ?? 0), 1);

        $thumbnail = $hotel->mainImage?->url ?? $hotel->images->first()?->url;

        $data = [
            'id' => $hotel->id,
            'name' => $hotel->nom,
            'description' => $hotel->description,
            'address' => $hotel->adresse,
            'city' => $hotel->ville,
            'rating' => $rating,
            'stars' => $rating >= 4 ? 5 : ($rating >= 3 ? 4 : 3),
            'thumbnail' => $thumbnail ? url('storage/'.$thumbnail) : null,
            'images' => $hotel->images->map(fn($i) => url('storage/'.$i->url))->toArray(),
            'amenities' => ['Wi-Fi', 'Climatisation', 'Petit-déjeuner'],
            'price_from' => (float) ($minPrice ?? 0),
            'room_count' => $hotel->typesChambre->count(),
        ];

        return $data;
    }
}
