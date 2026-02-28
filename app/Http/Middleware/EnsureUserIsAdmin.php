<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, ['SUPER_ADMIN', 'ADMIN'])) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
