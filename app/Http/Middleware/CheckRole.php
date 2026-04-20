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
        if(!auth()->check()){
            return response()->json([
                'message'=>'Non authentifie'
            ],401);
        }

        if(!in_array(auth()->user()->role,$roles)){
            return response()->json([
                'messgae'=>'Acces interdit, role insuffisant',
            ],403);
        }
    return $next($request);
    }

}
