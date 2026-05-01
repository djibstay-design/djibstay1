<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\Hotel;
use App\Models\TypeChambre;
use Illuminate\Http\Request;

class ChambreController extends Controller
{
    private function getHotel()
    {
        $user = auth()->user();
        return Hotel::where(fn($q) => $q->where('user_id',$user->id)->orWhere('admin_id',$user->id))->firstOrFail();
    }

    public function index()
    {
        $hotel   = $this->getHotel();
        $chambres = Chambre::whereHas('typeChambre', fn($q) => $q->where('hotel_id',$hotel->id))
            ->with('typeChambre')->orderBy('numero')->get();
        $types   = TypeChambre::where('hotel_id',$hotel->id)->get();
        return view('hotel_admin.chambres.index', compact('hotel','chambres','types'));
    }

    public function create()
    {
        $hotel = $this->getHotel();
        $types = TypeChambre::where('hotel_id',$hotel->id)->get();
        return view('hotel_admin.chambres.create', compact('hotel','types'));
    }

    public function store(Request $request)
    {
        $hotel     = $this->getHotel();
        $validated = $request->validate([
            'numero'   => ['required','string','max:20'],
            'etat'     => ['required','in:DISPONIBLE,OCCUPEE,MAINTENANCE'],
            'type_id'  => ['required','exists:types_chambre,id'],
        ]);
        $type = TypeChambre::findOrFail($validated['type_id']);
        abort_if($type->hotel_id !== $hotel->id, 403);
        Chambre::create($validated);
        return redirect()->route('hoteladmin.chambres.index')->with('success','Chambre créée.');
    }

    public function edit(Chambre $chambre)
    {
        $hotel = $this->getHotel();
        abort_if($chambre->typeChambre->hotel_id !== $hotel->id, 403);
        $types = TypeChambre::where('hotel_id',$hotel->id)->get();
        return view('hotel_admin.chambres.edit', compact('hotel','chambre','types'));
    }

    public function update(Request $request, Chambre $chambre)
    {
        $hotel     = $this->getHotel();
        abort_if($chambre->typeChambre->hotel_id !== $hotel->id, 403);
        $validated = $request->validate([
            'numero'  => ['required','string','max:20'],
            'etat'    => ['required','in:DISPONIBLE,OCCUPEE,MAINTENANCE'],
            'type_id' => ['required','exists:types_chambre,id'],
        ]);
        $chambre->update($validated);
        return redirect()->route('hoteladmin.chambres.index')->with('success','Chambre mise à jour.');
    }

    public function destroy(Chambre $chambre)
    {
        $hotel = $this->getHotel();
        abort_if($chambre->typeChambre->hotel_id !== $hotel->id, 403);
        $chambre->delete();
        return back()->with('success','Chambre supprimée.');
    }
}