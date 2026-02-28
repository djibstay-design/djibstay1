<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function index(): View
    {
        $hotels = Hotel::withCount(['typesChambre', 'avis'])->latest()->paginate(9);
        return view('hotels.index', compact('hotels'));
    }

    public function show(Hotel $hotel): View
    {
        $hotel->load([
            'typesChambre' => fn ($q) => $q->with(['chambres' => fn ($c) => $c->where('etat', 'DISPONIBLE')]),
            'avis',
        ]);
        return view('hotels.show', compact('hotel'));
    }
}
