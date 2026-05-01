<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use App\Models\Hotel;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    private function getHotel()
    {
        $user = auth()->user();
        return Hotel::where(fn($q) => $q->where('user_id',$user->id)->orWhere('admin_id',$user->id))->firstOrFail();
    }

    public function index()
    {
        $hotel = $this->getHotel();
        $avis  = Avis::where('hotel_id',$hotel->id)->latest()->paginate(15);
        $avg   = Avis::where('hotel_id',$hotel->id)->avg('note') ?: 0;
        return view('hotel_admin.avis.index', compact('hotel','avis','avg'));
    }

    public function repondre(Request $request, Avis $avis)
    {
        $hotel = $this->getHotel();
        abort_if($avis->hotel_id !== $hotel->id, 403);
        $request->validate(['reponse' => ['required','string','max:1000']]);
        $avis->update([
            'reponse_admin'         => $request->reponse,
            'reponse_admin_at'      => now(),
            'reponse_admin_user_id' => auth()->id(),
        ]);
        return back()->with('success','Réponse publiée.');
    }
}