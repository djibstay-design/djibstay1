<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        // ⚠️ MAINTENANCE DÉSACTIVÉE MANUELLEMENT
        return $next($request);
    }
}