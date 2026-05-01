<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Enregistrer un nouvel avis depuis l'app mobile.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hotel_id' => ['required', 'exists:hotels,id'],
            'note'     => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        $avis = Avis::create([
            'hotel_id'    => $validated['hotel_id'],
            'user_id'     => $user->id,
            'nom_client'  => $user->name,
            'note'        => $validated['note'],
            'commentaire' => $validated['commentaire'],
            'date_avis'   => now(),
        ]);

        return response()->json([
            'message' => 'Merci pour votre avis !',
            'data'    => $avis
        ], 201);
    }
}
