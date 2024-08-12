<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdmins
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next) : Response
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(401);
        }

        return $next($request);
    }
}