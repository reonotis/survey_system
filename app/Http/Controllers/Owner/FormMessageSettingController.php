<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\FormSetting;
use App\Service\FormMessageSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormMessageSettingController extends Controller
{
    /**
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        $form_setting->load('message');

        return view('owner.form.message-setting', [
            'form_setting' => $form_setting,
        ]);
    }

    /**
     * @param FormSetting $form_setting
     * @param Request $request TODO
     * @return RedirectResponse
     */
    public function update(FormSetting $form_setting, Request $request): RedirectResponse
    {
        $form_message_service = app(FormMessageSettingService::class);
        $form_message = $form_setting->message;
        if (is_null($form_message)) {
            // 登録処理
            $form_message = $form_message_service->create([
                'form_setting_id' => $form_setting->id,
                'outside_period_message' => $request->outside_period_message,
                'complete_message' => $request->complete_message,
            ]);
        } else {
            // 更新処理
            $form_message_service->update($form_message, [
                'outside_period_message' => $request->outside_period_message,
                'complete_message' => $request->complete_message,
            ]);
        }

        return redirect()->back()->with('success', 'メッセージ設定を更新しました。');
    }
}

