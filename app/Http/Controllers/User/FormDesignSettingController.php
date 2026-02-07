<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Models\FormSetting;
use App\Service\FormSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormDesignSettingController extends UserController
{
    /**
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        return view('user.form.basic.design-setting', [
            'form_setting' => $form_setting,
        ]);
    }

    /**
     */
    public function update(FormSetting $form_setting, Request $request): RedirectResponse
    {
        try {

            $service = app(FormSettingService::class);
            $service->update($form_setting, [
                'design_type' => $request->design_type,
            ]);

            return redirect()->back()->with('success', ['デザイン設定を更新しました']);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());
            return redirect()->back()->with('error', ['デザイン設定の更新に失敗しました']);
        }
    }
}
