<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelImage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HotelController extends Controller
{
  public function index(Request $request): View
{
    $user  = $request->user();
    $query = $user->role === 'SUPER_ADMIN'
        ? Hotel::with('user')->withCount(['typesChambre','avis'])
        : Hotel::where(fn($q) => $q->where('user_id',$user->id)->orWhere('admin_id',$user->id))
                ->with('user')->withCount(['typesChambre','avis']);

    if ($request->filled('q')) {
        $query->where(fn($q) => $q->where('nom','like',"%{$request->q}%")->orWhere('ville','like',"%{$request->q}%"));
    }

    $hotels = $query->latest()->paginate(10)->withQueryString();
    return view('admin.hotels.index', compact('hotels'));
}

    public function create(): View
    {
        $admins = User::where('role', 'ADMIN')->get();

        return view('admin.hotels.create', compact('admins'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:150'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'admin_id' => ['required', 'exists:users,id'],
            'main_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $hotelAdminId = $request->admin_id;
        // Si super admin, l'hôtel appartient à l'admin sélectionné. Sinon au créateur (partenaire).
        $ownerId = $request->user()->role === 'SUPER_ADMIN' ? $hotelAdminId : $request->user()->id;

        $hotel = \App\Models\Hotel::create(array_merge($validated, ['user_id' => $ownerId]));

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store(
                'hotels/'.$hotel->id,
                'public'
            );
            HotelImage::create([
                'hotel_id' => $hotel->id,
                'path' => $path,
                'is_main' => true,
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('admin.hotels.index')->with('success', 'Hôtel créé.');
    }

    public function edit(Request $request, Hotel $hotel): View|RedirectResponse
    {
        $this->authorizeHotel($request, $hotel);

        $admins = collect();
        if ($request->user()->role === 'SUPER_ADMIN') {
            $admins = User::query()
                ->where('role', 'ADMIN')
                ->orderBy('name')
                ->orderBy('prenom')
                ->get();
        }

        return view('admin.hotels.edit', compact('hotel', 'admins'));
    }

    public function update(Request $request, Hotel $hotel): RedirectResponse
    {
        $this->authorizeHotel($request, $hotel);

        $rules = [
            'nom' => ['required', 'string', 'max:150'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ];

        $canReassignHotelAdmin = $request->user()->role === 'SUPER_ADMIN'
            && User::query()->where('role', 'ADMIN')->exists();

        if ($canReassignHotelAdmin) {
            $rules['admin_id'] = [
                'required',
                Rule::exists('users', 'id')->where('role', 'ADMIN'),
            ];
        }

        $validated = $request->validate($rules);

        if (! $canReassignHotelAdmin) {
            unset($validated['admin_id']);
        }

        if ($canReassignHotelAdmin) {
            $validated['user_id'] = $validated['admin_id'];
        }

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
        $userId = $request->user()->id;
        if ($request->user()->role !== 'SUPER_ADMIN' && $hotel->user_id !== $userId && $hotel->admin_id !== $userId) {
            abort(403);
        }
    }
}
