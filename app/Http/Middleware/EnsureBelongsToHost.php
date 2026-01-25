<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBelongsToHost
{
    /**
     * ホスト（ドメインとの紐付いている）のフォームである事を確認する
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $form_setting = $request->route('form_setting');
        if ($form_setting === null) {
            return $next($request);
        }

        // ホストで設定したフォームならOK
        if ($form_setting->host_name === request()->getHost()) {
            return $next($request);
        }

        abort(404);
    }
}
