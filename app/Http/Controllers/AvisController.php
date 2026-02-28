<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hotel_id' => ['required', 'exists:hotels,id'],
            'nom_client' => ['required', 'string', 'max:100'],
            'email_client' => ['required', 'email'],
            'note' => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['nullable', 'string'],
        ]);

        $validated['date_avis'] = now()->toDateString();

        Avis::create($validated);

        return back()->with('success', 'Avis enregistré. Merci !');
    }
}
