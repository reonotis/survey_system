<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserAuthController extends Controller
{
    /**
     * ログインフォームを表示
     */
    public function showLoginForm(): View
    {
        return view('user.auth.login');
    }

    /**
     * ユーザーをログインさせる
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials['host'] = request()->getHost();

        if (Auth::guard('user')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/user/dashboard');
        }

        return back()->withErrors([
            'email' => '提供された認証情報が記録と一致しません。',
        ])->onlyInput('email');
    }

    /**
     * ユーザーをログアウトさせる
     */
    public function logout(Request $request): RedirectResponse
    {
        // userガードでのみログアウト
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/user/login');
    }
}
