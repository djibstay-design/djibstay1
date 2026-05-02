<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'CLIENT');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('prenom', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $clients = $query->withCount('reservations')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.clients.index', compact('clients'));
    }

    public function show(User $client): View
    {
        if ($client->role !== 'CLIENT') {
            abort(404);
        }

        $client->load(['reservations' => function ($q) {
            $q->with(['chambre.typeChambre.hotel'])->orderByDesc('created_at');
        }]);

        return view('admin.clients.show', compact('client'));
    }

    public function edit(User $client): View
    {
        if ($client->role !== 'CLIENT') {
            abort(404);
        }

        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, User $client): RedirectResponse
    {
        if ($client->role !== 'CLIENT') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'prenom' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email,' . $client->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $validated['name'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $client->update($data);

        return redirect()->route('admin.clients.index')->with('success', 'Informations du client mises à jour.');
    }

    public function toggleStatus(User $client): RedirectResponse
    {
        if ($client->role !== 'CLIENT') {
            abort(404);
        }

        $client->update([
            'is_suspended' => !$client->is_suspended
        ]);

        $status = $client->is_suspended ? 'suspendu' : 'activé';

        return back()->with('success', "Le compte du client a été {$status}.");
    }

    public function destroy(User $client): RedirectResponse
    {
        if ($client->role !== 'CLIENT') {
            abort(404);
        }

        // On pourrait vérifier s'il a des réservations actives
        if ($client->reservations()->whereIn('statut', ['EN_ATTENTE', 'CONFIRMEE'])->exists()) {
            return back()->with('error', 'Impossible de supprimer un client ayant des réservations actives.');
        }

        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Client supprimé avec succès.');
    }
}
