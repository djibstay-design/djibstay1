<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HotelImageController extends Controller
{
    public function index(Request $request): View
    {
        $hotelIds = $this->getAuthorizedHotelIds($request);
        $images = HotelImage::whereIn('hotel_id', $hotelIds)
            ->with('hotel:id,nom')
            ->orderBy('hotel_id')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12);

        return view('admin.images.index', compact('images'));
    }

    public function create(Request $request): View
    {
        $hotels = $this->getAuthorizedHotels($request);
        return view('admin.images.create', compact('hotels'));
    }

    public function store(Request $request): RedirectResponse
    {
        $hotels = $this->getAuthorizedHotels($request);
        $validated = $request->validate([
            'hotel_id' => ['required', 'exists:hotels,id'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'is_main_index' => ['nullable', 'integer', 'min:0'],
        ]);

        $hotelId = (int) $validated['hotel_id'];
        if (!$hotels->contains('id', $hotelId)) {
            abort(403);
        }

        $isMainIndex = isset($validated['is_main_index']) ? (int) $validated['is_main_index'] : null;
        $maxOrder = HotelImage::where('hotel_id', $hotelId)->max('sort_order') ?? 0;

        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('hotel-images', 'public');
            $isMain = ($isMainIndex !== null && $isMainIndex === $index + 1);
            if ($isMain) {
                HotelImage::where('hotel_id', $hotelId)->update(['is_main' => false]);
            }
            HotelImage::create([
                'hotel_id' => $hotelId,
                'path' => $path,
                'is_main' => $isMain,
                'sort_order' => ++$maxOrder,
            ]);
        }

        return redirect()->route('admin.images.index')->with('success', 'Image(s) ajoutée(s) avec succès.');
    }

    public function edit(Request $request, HotelImage $image): View|RedirectResponse
    {
        $this->authorizeImage($request, $image);
        $hotels = $this->getAuthorizedHotels($request);
        return view('admin.images.edit', compact('image', 'hotels'));
    }

    public function update(Request $request, HotelImage $image): RedirectResponse
    {
        $this->authorizeImage($request, $image);

        $validated = $request->validate([
            'is_main' => ['nullable', 'in:1,on'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        if (!empty($validated['is_main'])) {
            HotelImage::where('hotel_id', $image->hotel_id)->update(['is_main' => false]);
            $image->update(['is_main' => true]);
        }

        if (isset($validated['sort_order'])) {
            $image->update(['sort_order' => (int) $validated['sort_order']]);
        }

        if ($request->hasFile('image')) {
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
            $image->update(['path' => $request->file('image')->store('hotel-images', 'public')]);
        }

        return redirect()->route('admin.images.index')->with('success', 'Image mise à jour.');
    }

    public function destroy(Request $request, HotelImage $image): RedirectResponse
    {
        $this->authorizeImage($request, $image);
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        $image->delete();
        return redirect()->route('admin.images.index')->with('success', 'Image supprimée.');
    }

    private function getAuthorizedHotelIds(Request $request): array
    {
        return $this->getAuthorizedHotels($request)->pluck('id')->all();
    }

    private function getAuthorizedHotels(Request $request)
    {
        $userId = $request->user()->id;
        return $request->user()->role === 'SUPER_ADMIN'
            ? Hotel::orderBy('nom')->get(['id', 'nom'])
            : Hotel::where(fn ($q) => $q->where('user_id', $userId)->orWhere('admin_id', $userId))->orderBy('nom')->get(['id', 'nom']);
    }

    private function authorizeImage(Request $request, HotelImage $image): void
    {
        $hotelIds = $this->getAuthorizedHotelIds($request);
        if (!in_array($image->hotel_id, $hotelIds, true)) {
            abort(403);
        }
    }
}
