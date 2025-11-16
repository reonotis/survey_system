<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $guard = null): Response
    {
        // セッションが開始されていることを確認
        if (!$request->hasSession()) {
            return response()->json([
                'message' => 'Session not started.',
                'debug' => 'Session middleware not applied.',
            ], 401);
        }

        // 認証チェック
        if (!Auth::guard($guard)->check()) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'debug' => [
                    'guard' => $guard,
                    'session_id' => $request->session()->getId(),
                    'user_in_session' => $request->session()->has('login_web_' . sha1($guard ?? 'web')),
                ],
            ], 401);
        }

        return $next($request);
    }
}

