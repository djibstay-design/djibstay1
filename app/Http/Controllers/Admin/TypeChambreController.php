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
            $query->whereHas('hotel', fn ($q) => $q->where('user_id', $user->id));
        }
        $typesChambre = $query->latest()->paginate(10);
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
            'prix_par_nuit' => ['required', 'numeric', 'min:0'],
        ]);

        if (! $hotels->contains('id', $validated['hotel_id'])) {
            abort(403);
        }

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
            'prix_par_nuit' => ['required', 'numeric', 'min:0'],
        ]);

        if (! $hotels->contains('id', $validated['hotel_id'])) {
            abort(403);
        }

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
        return $request->user()->role === 'SUPER_ADMIN'
            ? Hotel::orderBy('nom')->get()
            : Hotel::where('user_id', $request->user()->id)->orderBy('nom')->get();
    }

    private function authorizeTypeChambre(Request $request, TypeChambre $typeChambre): void
    {
        if ($request->user()->role !== 'SUPER_ADMIN' && $typeChambre->hotel->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
