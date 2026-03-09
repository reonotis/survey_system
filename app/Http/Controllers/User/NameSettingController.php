<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Http\Requests\User\NameSettingRequest;
use App\Service\UserService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class NameSettingController extends UserController
{
    private UserService $user_service;

    /**
     * コンストラクタ
     */
    public function __construct(UserService $user_service)
    {
        parent::__construct();

        $this->user_service = $user_service;
    }

    /**
     */
    public function index(): View
    {
        return view('user.name-setting', [
        ]);
    }

    /**
     * @param NameSettingRequest $request
     * @return RedirectResponse
     */
    public function update(NameSettingRequest $request): RedirectResponse
    {
        try {
            $this->user_service->update($this->my_user, [
                'name' => $request->name
            ]);
            return redirect()->route('user_dashboard')->with('success', ['更新しました']);
        } catch (Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['更新に失敗しました。']);
        }
    }
}
