<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Per-hotel image management (nested under admin/hotels/{hotel}/images).
 * Handles: view gallery, upload, set main, delete.
 */
class HotelHotelImageController extends Controller
{
    public function index(Request $request, Hotel $hotel): View|RedirectResponse
    {
        $this->authorizeHotel($request, $hotel);
        $hotel->load('images');
        return view('admin.hotels.images', compact('hotel'));
    }

    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $this->authorizeHotel($request, $hotel);

        $validated = $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'set_as_main' => ['nullable', 'in:1,on'],
        ]);

        $maxOrder = $hotel->images()->max('sort_order') ?? 0;
        $setAsMain = $request->boolean('set_as_main');
        $isFirst = true;

        foreach ($request->file('images') as $file) {
            $path = $file->store('hotels/' . $hotel->id, 'public');
            $isMain = $setAsMain && $isFirst;
            if ($isMain) {
                $hotel->images()->update(['is_main' => false]);
            }
            $hotel->images()->create([
                'path' => $path,
                'is_main' => $isMain,
                'sort_order' => ++$maxOrder,
            ]);
            $isFirst = false;
        }

        return redirect()
            ->route('admin.hotels.images.index', $hotel)
            ->with('success', count($request->file('images')) > 1 ? 'Images ajoutées.' : 'Image ajoutée.');
    }

    public function setMain(Request $request, Hotel $hotel, HotelImage $image): RedirectResponse
    {
        $this->authorizeHotel($request, $hotel);
        if ($image->hotel_id !== $hotel->id) {
            abort(404);
        }

        $hotel->images()->update(['is_main' => false]);
        $image->update(['is_main' => true]);

        return redirect()
            ->route('admin.hotels.images.index', $hotel)
            ->with('success', 'Image principale mise à jour.');
    }

    public function destroy(Request $request, Hotel $hotel, HotelImage $image): RedirectResponse
    {
        $this->authorizeHotel($request, $hotel);
        if ($image->hotel_id !== $hotel->id) {
            abort(404);
        }

        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        $image->delete();

        return redirect()
            ->route('admin.hotels.images.index', $hotel)
            ->with('success', 'Image supprimée.');
    }

    private function authorizeHotel(Request $request, Hotel $hotel): void
    {
        $userId = $request->user()->id;
        if ($request->user()->role !== 'SUPER_ADMIN' && $hotel->user_id !== $userId && $hotel->admin_id !== $userId) {
            abort(403);
        }
    }
}
