<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OwnerAuthController extends Controller
{
    /**
     * ログインフォームを表示
     */
    public function showLoginForm(): View
    {
        return view('owner.auth.login');
    }

    /**
     * オーナーをログインさせる
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('owner')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/owner/dashboard');
        }

        return back()->withErrors([
            'email' => '提供された認証情報が記録と一致しません。',
        ])->onlyInput('email');
    }

    /**
     * オーナーをログアウトさせる
     */
    public function logout(Request $request): RedirectResponse
    {
        // ownerガードでのみログアウト
        if (Auth::guard('owner')->check()) {
            Auth::guard('owner')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/owner/login');
    }
}
