<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class MonHotelController extends Controller
{
    private function getHotel()
    {
        $user = auth()->user();
        return Hotel::where(fn($q) => $q->where('user_id',$user->id)->orWhere('admin_id',$user->id))
            ->with(['typesChambre','images','mainImage'])
            ->firstOrFail();
    }

    public function edit()
    {
        $hotel = $this->getHotel();
        return view('hotel_admin.hotel.edit', compact('hotel'));
    }

    public function update(Request $request)
    {
        $hotel = $this->getHotel();
        $validated = $request->validate([
            'nom'         => ['required','string','max:150'],
            'adresse'     => ['nullable','string','max:255'],
            'ville'       => ['nullable','string','max:100'],
            'description' => ['nullable','string'],
        ]);
        $hotel->update($validated);
        return back()->with('success','Hôtel mis à jour avec succès.');
    }
}