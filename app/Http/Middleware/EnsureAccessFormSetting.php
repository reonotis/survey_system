<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\FormSetting;
use App\Repositories\FormSettingUserRepository;
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

        /** @var FormSetting $form_setting */
        $form_setting = $request->route('form_setting');
        if ($form_setting === null) {
            return $next($request);
        }

       $user = Auth::guard('user')->user();

        // フォームのオーナーであればOK
        if ($user->id === $form_setting->owner_user) {
            return $next($request);
        }

        // 招待されていればOK
        $exist_relation = app(FormSettingUserRepository::class)->checkRelation($user->id, $form_setting->id);
        if ($exist_relation) {
            return $next($request);
        }

        abort(404);
    }
}
