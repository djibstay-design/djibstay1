<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsHotelAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!in_array($request->user()->role, ['ADMIN', 'SUPER_ADMIN'])) {
            abort(403, 'Accès réservé aux administrateurs d\'hôtel.');
        }

        return $next($request);
    }
}