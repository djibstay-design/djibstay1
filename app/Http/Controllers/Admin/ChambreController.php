<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\RoomImage;
use App\Models\TypeChambre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ChambreController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Chambre::with('typeChambre.hotel');
        if ($user->role !== 'SUPER_ADMIN') {
            $query->whereHas('typeChambre.hotel', fn ($q) => $q->where('user_id', $user->id)->orWhere('admin_id', $user->id));
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('numero', 'like', "%{$q}%")
                    ->orWhereHas('typeChambre', fn ($t) => $t->where('nom_type', 'like', "%{$q}%"))
                    ->orWhereHas('typeChambre.hotel', fn ($h) => $h->where('nom', 'like', "%{$q}%"));
            });
        }
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }
        $chambres = $query->latest()->paginate(10)->withQueryString();
        return view('admin.chambres.index', compact('chambres'));
    }

    public function create(Request $request): View
    {
        $typesChambre = $this->getTypesChambreForUser($request);
        return view('admin.chambres.create', compact('typesChambre'));
    }

    public function store(Request $request): RedirectResponse
    {
        $typesChambre = $this->getTypesChambreForUser($request);
        $validated = $request->validate([
            'type_id' => ['required', 'exists:types_chambre,id'],
            'numero' => ['required', 'string', 'max:20'],
            'etat' => ['required', 'in:DISPONIBLE,OCCUPEE,MAINTENANCE'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
        ]);

        if (! $typesChambre->contains('id', $validated['type_id'])) {
            abort(403);
        }

        $chambre = Chambre::create([
            'type_id' => $validated['type_id'],
            'numero' => $validated['numero'],
            'etat' => $validated['etat'],
        ]);

        $typeId = (int) $validated['type_id'];
        if ($request->hasFile('images')) {
            $sortOrder = (int) RoomImage::where('type_chambre_id', $typeId)->max('sort_order');
            foreach ($request->file('images') as $file) {
                $path = $file->store('room-images/' . $typeId, 'public');
                RoomImage::create([
                    'type_chambre_id' => $typeId,
                    'path' => $path,
                    'sort_order' => ++$sortOrder,
                ]);
            }
        }

        return redirect()->route('admin.chambres.index')->with('success', 'Chambre créée.');
    }

    public function edit(Request $request, Chambre $chambre): View|RedirectResponse
    {
        $this->authorizeChambre($request, $chambre);
        $typesChambre = $this->getTypesChambreForUser($request);
        return view('admin.chambres.edit', compact('chambre', 'typesChambre'));
    }

    public function update(Request $request, Chambre $chambre): RedirectResponse
    {
        $this->authorizeChambre($request, $chambre);
        $typesChambre = $this->getTypesChambreForUser($request);

        $validated = $request->validate([
            'type_id' => ['required', 'exists:types_chambre,id'],
            'numero' => ['required', 'string', 'max:20'],
            'etat' => ['required', 'in:DISPONIBLE,OCCUPEE,MAINTENANCE'],
        ]);

        if (! $typesChambre->contains('id', $validated['type_id'])) {
            abort(403);
        }

        $chambre->update($validated);

        return redirect()->route('admin.chambres.index')->with('success', 'Chambre mise à jour.');
    }

    public function destroy(Request $request, Chambre $chambre): RedirectResponse
    {
        $this->authorizeChambre($request, $chambre);
        $chambre->delete();
        return redirect()->route('admin.chambres.index')->with('success', 'Chambre supprimée.');
    }

    private function getTypesChambreForUser(Request $request)
    {
        $query = TypeChambre::with('hotel')->orderBy('nom_type');
        if ($request->user()->role !== 'SUPER_ADMIN') {
            $userId = $request->user()->id;
            $query->whereHas('hotel', fn ($q) => $q->where('user_id', $userId)->orWhere('admin_id', $userId));
        }
        return $query->get();
    }

    private function authorizeChambre(Request $request, Chambre $chambre): void
    {
        $userId = $request->user()->id;
        $hotel = $chambre->typeChambre->hotel;
        if ($request->user()->role !== 'SUPER_ADMIN' && $hotel->user_id !== $userId && $hotel->admin_id !== $userId) {
            abort(403);
        }
    }
}
