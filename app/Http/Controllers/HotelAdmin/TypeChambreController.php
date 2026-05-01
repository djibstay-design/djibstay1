<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RoomImage;
use App\Models\TypeChambre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TypeChambreController extends Controller
{
    private function getHotel()
    {
        $user = auth()->user();
        return Hotel::where(fn($q) => $q->where('user_id',$user->id)->orWhere('admin_id',$user->id))->firstOrFail();
    }

    public function index()
    {
        $hotel = $this->getHotel();
        $types = TypeChambre::where('hotel_id',$hotel->id)->with(['chambres','images'])->get();
        return view('hotel_admin.types.index', compact('hotel','types'));
    }

    public function create()
    {
        $hotel = $this->getHotel();
        return view('hotel_admin.types.create', compact('hotel'));
    }

    public function store(Request $request)
    {
        $hotel     = $this->getHotel();
        $validated = $this->validateType($request);
        $type      = TypeChambre::create(array_merge($validated, ['hotel_id' => $hotel->id]));
        $this->handleImages($request, $type);
        return redirect()->route('hoteladmin.types-chambre.index')->with('success','Type de chambre créé.');
    }

    public function edit(TypeChambre $typeChambre)
    {
        $hotel = $this->getHotel();
        abort_if($typeChambre->hotel_id !== $hotel->id, 403);
        $typeChambre->load('images');
        return view('hotel_admin.types.edit', compact('hotel','typeChambre'));
    }

    public function update(Request $request, TypeChambre $typeChambre)
    {
        $hotel = $this->getHotel();
        abort_if($typeChambre->hotel_id !== $hotel->id, 403);
        $validated = $this->validateType($request);
        $typeChambre->update($validated);
        $this->handleImages($request, $typeChambre);
        return redirect()->route('hoteladmin.types-chambre.index')->with('success','Type de chambre mis à jour.');
    }

    public function destroy(TypeChambre $typeChambre)
    {
        $hotel = $this->getHotel();
        abort_if($typeChambre->hotel_id !== $hotel->id, 403);
        $typeChambre->delete();
        return back()->with('success','Type de chambre supprimé.');
    }

    private function validateType(Request $request): array
    {
        return $request->validate([
            'nom_type'               => ['required','string','max:100'],
            'capacite'               => ['required','integer','min:1'],
            'prix_par_nuit'          => ['required','numeric','min:0'],
            'superficie_m2'          => ['nullable','numeric'],
            'lit_description'        => ['nullable','string','max:200'],
            'has_wifi'               => ['boolean'],
            'has_climatisation'      => ['boolean'],
            'has_minibar'            => ['boolean'],
            'description'            => ['nullable','string'],
            'equipements_salle_bain' => ['nullable','string'],
            'equipements_generaux'   => ['nullable','string'],
        ]);
    }

    private function handleImages(Request $request, TypeChambre $type): void
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('room-images/'.$type->id, 'public');
                RoomImage::create(['type_chambre_id'=>$type->id,'path'=>$path,'sort_order'=>$type->images()->count()]);
            }
        }
    }
}