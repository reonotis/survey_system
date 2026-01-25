<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccessFormSetting
{
    /**
     * ユーザーがアクセスして良いフォームである事を確認する
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

        $form_setting = $request->route('form_setting');
        if ($form_setting === null) {
            return $next($request);
        }

        if (Auth::guard('user')->user()->id === $form_setting->created_by_user) {
            return $next($request);
        }

        abort(404);
    }
}
