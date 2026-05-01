<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    private function getHotel()
    {
        $user = auth()->user();
        return Hotel::where(fn($q) => $q->where('user_id',$user->id)->orWhere('admin_id',$user->id))
            ->with('images')
            ->firstOrFail();
    }

    public function index()
    {
        $hotel = $this->getHotel();
        return view('hotel_admin.photos.index', compact('hotel'));
    }

    public function store(Request $request)
    {
        $hotel = $this->getHotel();
        $request->validate(['photos.*' => ['required','image','mimes:jpeg,jpg,png,webp','max:5120']]);

        foreach ($request->file('photos', []) as $file) {
            $path = $file->store('hotel-images', 'public');
            HotelImage::create([
                'hotel_id'   => $hotel->id,
                'path'       => $path,
                'is_main'    => $hotel->images->isEmpty(),
                'sort_order' => $hotel->images->count(),
            ]);
        }
        return back()->with('success', 'Photos ajoutées avec succès.');
    }

    public function setMain(HotelImage $image)
    {
        $hotel = $this->getHotel();
        HotelImage::where('hotel_id', $hotel->id)->update(['is_main' => false]);
        $image->update(['is_main' => true]);
        return back()->with('success', 'Photo principale définie.');
    }

    public function destroy(HotelImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return back()->with('success', 'Photo supprimée.');
    }
}