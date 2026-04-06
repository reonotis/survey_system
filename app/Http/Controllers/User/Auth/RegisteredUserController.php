<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Auth;

use App\Consts\MailConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\RegisteredUserStoreRequest;
use App\Service\MailService;
use App\Service\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('user.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(RegisteredUserStoreRequest $request): RedirectResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                // ユーザーの登録
                $user = app(UserService::class)->create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // 新しいユーザーが登録されたので通知メールを送る
                $mail_service = app(MailService::class);
                $mail_service->sendMail(MailConst::USER_REGISTER, $request->validated());

                Auth::guard('user')->login($user);

                return redirect(route('user_dashboard', absolute: false));
            });
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['登録に失敗しました']);
        }
    }
}
