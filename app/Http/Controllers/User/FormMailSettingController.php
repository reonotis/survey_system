<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Models\FormSetting;
use App\Service\FormMailSettingService;
use App\Service\MailTemplateService;
use App\Traits\InputSettingTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormMailSettingController extends UserController
{
    use InputSettingTrait;

    private MailTemplateService $mail_template_service;

    /**
     * コンストラクタ
     */
    public function __construct(MailTemplateService $mail_template_service)
    {
        parent::__construct();
        $this->mail_template_service = $mail_template_service;
    }

    /**
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        $form_setting->load('mailSetting');

        $mail_template_collection = $this->mail_template_service->getMailTemplateList(
            $this->my_user->id,
        );

        $mail_template_list = $this->makeSectionItemsByCollection($mail_template_collection, false, 'template_name', null);

        return view('user.form.basic.mail-setting', [
            'form_setting' => $form_setting,
            'mail_template_list' => $mail_template_list,
        ]);
    }

    /**
     * @param FormSetting $form_setting
     * @param Request $request TODO
     * @return RedirectResponse
     */
    public function update(FormSetting $form_setting, Request $request): RedirectResponse
    {
        try {
            $mail_message_service = app(FormMailSettingService::class);

            $param = [
                'notification_mail_flg' => $request->notification_mail_flg,
                'notification_mail_title' => $request->notification_mail_title,
                'notification_mail_address' => $request->notification_mail_address,
                'notification_mail_message' => $request->notification_mail_message,
                'auto_reply_mail_flg' => $request->auto_reply_mail_flg,
                'auto_reply_mail_title' => $request->auto_reply_mail_title,
                'auto_reply_mail_message' => $request->auto_reply_mail_message,
            ];

            $mail_message = $form_setting->mailSetting;
            if (is_null($mail_message)) {
                // 登録処理
                $param['form_setting_id'] = $form_setting->id;
                $mail_message_service->create($param);
            } else {
                // 更新処理
                $mail_message_service->update($mail_message, $param);
            }

            return redirect()->back()->with('success', ['メール設定を更新しました']);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());
            return redirect()->back()->with('error', ['更新に失敗しました']);
        }
    }
}
