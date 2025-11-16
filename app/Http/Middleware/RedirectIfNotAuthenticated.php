<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $guard = null): Response
    {
        if (!Auth::guard($guard)->check()) {
            $login_route = match($guard) {
                'admin' => 'admin_login',
                'owner' => 'owner_login',
                'web' => 'user_login',
                default => 'login',
            };

            return redirect()->route($login_route);
        }

        return $next($request);
    }
}
