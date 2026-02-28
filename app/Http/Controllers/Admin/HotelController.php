<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $hotels = $user->role === 'SUPER_ADMIN'
            ? Hotel::with('user')->withCount(['typesChambre', 'avis'])->latest()->paginate(10)
            : Hotel::where('user_id', $user->id)->withCount(['typesChambre', 'avis'])->latest()->paginate(10);

        return view('admin.hotels.index', compact('hotels'));
    }

    public function create(): View
    {
        return view('admin.hotels.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:150'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        $request->user()->hotels()->create($validated);

        return redirect()->route('admin.hotels.index')->with('success', 'Hôtel créé.');
    }

    public function edit(Request $request, Hotel $hotel): View|RedirectResponse
    {
        $this->authorizeHotel($request, $hotel);
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel): RedirectResponse
    {
        $this->authorizeHotel($request, $hotel);

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:150'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        $hotel->update($validated);

        return redirect()->route('admin.hotels.index')->with('success', 'Hôtel mis à jour.');
    }

    public function destroy(Request $request, Hotel $hotel): RedirectResponse
    {
        $this->authorizeHotel($request, $hotel);
        $hotel->delete();
        return redirect()->route('admin.hotels.index')->with('success', 'Hôtel supprimé.');
    }

    private function authorizeHotel(Request $request, Hotel $hotel): void
    {
        if ($request->user()->role !== 'SUPER_ADMIN' && $hotel->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
