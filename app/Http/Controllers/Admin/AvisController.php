<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AvisController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Avis::with(['hotel', 'reponseAdminUser']);
        if ($user->role !== 'SUPER_ADMIN') {
            $query->whereHas('hotel', fn ($q) => $q->where('user_id', $user->id)->orWhere('admin_id', $user->id));
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('nom_client', 'like', "%{$q}%")
                    ->orWhere('email_client', 'like', "%{$q}%")
                    ->orWhere('commentaire', 'like', "%{$q}%")
                    ->orWhereHas('hotel', fn ($h) => $h->where('nom', 'like', "%{$q}%"));
            });
        }
        $avis = $query->latest('date_avis')->paginate(15)->withQueryString();
        return view('admin.avis.index', compact('avis'));
    }

    public function repondre(Request $request, Avis $avi): RedirectResponse
    {
        $user = $request->user();
        if ($user->role !== 'SUPER_ADMIN' && $avi->hotel->user_id !== $user->id && $avi->hotel->admin_id !== $user->id) {
            abort(403);
        }
        $validated = $request->validate([
            'reponse_admin' => ['required', 'string', 'max:2000'],
        ], [
            'reponse_admin.required' => 'Veuillez saisir une réponse.',
            'reponse_admin.max' => 'La réponse ne doit pas dépasser 2000 caractères.',
        ]);
        $avi->update([
            'reponse_admin' => $request->reponse_admin,
            'reponse_admin_at' => now(),
            'reponse_admin_user_id' => $user->id,
        ]);
        return redirect()->route('admin.avis.index')->with('success', 'Réponse enregistrée.');
    }
}
