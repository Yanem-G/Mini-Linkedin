<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle($request, Closure $next, ...$roles)
    {
    if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
        return response()->json(['error' => 'Accès non autorisé'], 403);
    }
    return $next($request);
    }

}
