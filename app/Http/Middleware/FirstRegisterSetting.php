<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FirstRegisterSetting
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ユーザーでログインしていなければチェックしない
        if (!Auth::guard('user')->check()) {
            return $next($request);
        }

        $user = Auth::guard('user')->user();

        if (is_null($user->name)) {
            // 名前設定時はスルー
            $route_list = ['user_name_setting', 'user_name_setting_update'];
            if (!in_array( $request->route()->getName(), $route_list)) {
                return redirect()->route('user_name_setting');
            }
        }

        return $next($request);
    }
}
