<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvisController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Avis::with('hotel');
        if ($user->role !== 'SUPER_ADMIN') {
            $query->whereHas('hotel', fn ($q) => $q->where('user_id', $user->id));
        }
        $avis = $query->latest('date_avis')->paginate(15);
        return view('admin.avis.index', compact('avis'));
    }
}
