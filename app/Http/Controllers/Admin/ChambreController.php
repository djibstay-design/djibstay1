<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\TypeChambre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChambreController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Chambre::with('typeChambre.hotel');
        if ($user->role !== 'SUPER_ADMIN') {
            $query->whereHas('typeChambre.hotel', fn ($q) => $q->where('user_id', $user->id));
        }
        $chambres = $query->latest()->paginate(10);
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
        ]);

        if (! $typesChambre->contains('id', $validated['type_id'])) {
            abort(403);
        }

        Chambre::create($validated);

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
            $query->whereHas('hotel', fn ($q) => $q->where('user_id', $request->user()->id));
        }
        return $query->get();
    }

    private function authorizeChambre(Request $request, Chambre $chambre): void
    {
        if ($request->user()->role !== 'SUPER_ADMIN' && $chambre->typeChambre->hotel->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
