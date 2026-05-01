<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\TypeChambre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TypeChambreController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = TypeChambre::with('hotel');
        if ($user->role !== 'SUPER_ADMIN') {
            $query->whereHas('hotel', fn ($q) => $q->where('user_id', $user->id)->orWhere('admin_id', $user->id));
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('nom_type', 'like', "%{$q}%")
                    ->orWhereHas('hotel', fn ($h) => $h->where('nom', 'like', "%{$q}%"));
            });
        }
        $typesChambre = $query->latest()->paginate(10)->withQueryString();

        return view('admin.types-chambre.index', compact('typesChambre'));
    }

    public function create(Request $request): View
    {
        $hotels = $this->getHotelsForUser($request);

        return view('admin.types-chambre.create', compact('hotels'));
    }

    public function store(Request $request): RedirectResponse
    {
        $hotels = $this->getHotelsForUser($request);
        $validated = $request->validate([
            'hotel_id' => ['required', 'exists:hotels,id'],
            'nom_type' => ['required', 'string', 'max:100'],
            'capacite' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'superficie_m2' => ['nullable', 'integer', 'min:1', 'max:500'],
            'lit_description' => ['nullable', 'string', 'max:255'],
            'equipements_salle_bain' => ['nullable', 'string', 'max:5000'],
            'equipements_generaux' => ['nullable', 'string', 'max:5000'],
            'prix_par_nuit' => ['required', 'numeric', 'min:0'],
        ]);

        if (! $hotels->contains('id', $validated['hotel_id'])) {
            abort(403);
        }

        $validated['has_climatisation'] = $request->boolean('has_climatisation');
        $validated['has_minibar'] = $request->boolean('has_minibar');
        $validated['has_wifi'] = $request->boolean('has_wifi');

        TypeChambre::create($validated);

        return redirect()->route('admin.types-chambre.index')->with('success', 'Type de chambre créé.');
    }

    public function edit(Request $request, TypeChambre $typeChambre): View|RedirectResponse
    {
        $this->authorizeTypeChambre($request, $typeChambre);
        $hotels = $this->getHotelsForUser($request);

        return view('admin.types-chambre.edit', compact('typeChambre', 'hotels'));
    }

    public function update(Request $request, TypeChambre $typeChambre): RedirectResponse
    {
        $this->authorizeTypeChambre($request, $typeChambre);
        $hotels = $this->getHotelsForUser($request);

        $validated = $request->validate([
            'hotel_id' => ['required', 'exists:hotels,id'],
            'nom_type' => ['required', 'string', 'max:100'],
            'capacite' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'superficie_m2' => ['nullable', 'integer', 'min:1', 'max:500'],
            'lit_description' => ['nullable', 'string', 'max:255'],
            'equipements_salle_bain' => ['nullable', 'string', 'max:5000'],
            'equipements_generaux' => ['nullable', 'string', 'max:5000'],
            'prix_par_nuit' => ['required', 'numeric', 'min:0'],
        ]);

        if (! $hotels->contains('id', $validated['hotel_id'])) {
            abort(403);
        }

        $validated['has_climatisation'] = $request->boolean('has_climatisation');
        $validated['has_minibar'] = $request->boolean('has_minibar');
        $validated['has_wifi'] = $request->boolean('has_wifi');

        $typeChambre->update($validated);

        return redirect()->route('admin.types-chambre.index')->with('success', 'Type de chambre mis à jour.');
    }

    public function destroy(Request $request, TypeChambre $typeChambre): RedirectResponse
    {
        $this->authorizeTypeChambre($request, $typeChambre);
        $typeChambre->delete();

        return redirect()->route('admin.types-chambre.index')->with('success', 'Type de chambre supprimé.');
    }

    private function getHotelsForUser(Request $request)
    {
        $userId = $request->user()->id;

        return $request->user()->role === 'SUPER_ADMIN'
            ? Hotel::orderBy('nom')->get()
            : Hotel::where(fn ($q) => $q->where('user_id', $userId)->orWhere('admin_id', $userId))->orderBy('nom')->get();
    }

    private function authorizeTypeChambre(Request $request, TypeChambre $typeChambre): void
    {
        $userId = $request->user()->id;
        if ($request->user()->role !== 'SUPER_ADMIN' && $typeChambre->hotel->user_id !== $userId && $typeChambre->hotel->admin_id !== $userId) {
            abort(403);
        }
    }
}
