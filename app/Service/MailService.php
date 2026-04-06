<?php

declare(strict_types=1);

namespace App\Service;

use App\Consts\CommonConst;
use App\Consts\MailConst;
use App\Traits\FormReplaceTrait;
use App\Mail\ContactMail;
use App\Mail\InviteMemberFirstRegister;
use App\Mail\InviteMember;
use App\Mail\UserRegisterMail;
use App\Models\FormSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailService
{
    use FormReplaceTrait;
    private string $admin_address = 'fujisawa@reonotis.jp'; // TODO envにする事

    /**
     * @param int $type
     * @param array $request
     * @return void
     */
    public function sendMail(int $type, array $request): void
    {
        match ($type) {
            1 => $this->sendUserRegisterMail($request),
        };
    }

    /**
     * @param array $request
     * @return void
     */
    private function sendUserRegisterMail(array $request): void
    {
        Mail::to($this->admin_address)
            ->send(new UserRegisterMail(
                $request['name'],
                $request['email'],
            ));
    }

    /**
     */
    public function sendContactMail(array $request): void
    {
        Mail::to($this->admin_address)
            ->send(new ContactMail(
                Auth::guard('user')->user()->name,
                Auth::guard('user')->user()->email,
                $request['message'],
            ));
    }

    /**
     * @param int $type
     * @param array $param
     * @return void
     */
    public function sendMailPattern(int $type, array $param): void
    {
        match ($type) {
            MailConst::INVITE_MEMBER_FIRST_REGISTER => $this->sendInviteFirstRegisterMail($param),
            MailConst::INVITE_MEMBER => $this->sendInviteMail($param),
            default => null,
        };
    }

    /**
     * @param array $param
     * @return void
     */
    private function sendInviteFirstRegisterMail(array $param): void
    {
        Mail::to($param['to_mail_address'])->send(new InviteMemberFirstRegister(
            $param['to_mail_address'],
            $param['form_name'],
            $param['owner_name'],
            $param['password'],
        ));
    }

    /**
     * @param array $param
     */
    private function sendInviteMail(array $param): void
    {
        Mail::to($param['to_mail_address'])->send(new InviteMember(
            $param['form_name'],
            $param['owner_name'],
        ));
    }

    /**
     * 通知メールを送信する
     */
    public function sendNotificationMail(FormSetting $form_setting, array $request_data): void
    {
        // 通知メールを送るか確認
        if (!$this->checkNotificationMail($form_setting)) {
            return;
        }

        $body = $this->replacePlaceholders($form_setting, $request_data, $form_setting->mailSetting->notification_mail_message);
        Mail::html($body, function ($message) use ($form_setting) {
            $message
                ->to($form_setting->mailSetting->notification_mail_address)
                ->subject($form_setting->mailSetting->notification_mail_title ?? '通知メール');
        });
    }

    /**
     * 自動返信メールを送信する
     */
    public function sendAutoReplyMail(FormSetting $form_setting, array $request_data): void
    {
        // 自動返信メールを送るか確認
        if (!$this->checkAutoReplyMail($form_setting, $request_data)) {
            return;
        }

        $body = $this->replacePlaceholders($form_setting, $request_data, $form_setting->mailSetting->auto_reply_mail_message);

        $to_email = $request_data['email'];

        Mail::html($body, function ($message) use ($form_setting, $to_email) {
            $message
                ->to($to_email)
                ->subject($form_setting->mailSetting->auto_reply_mail_title ?? '自動返信メール');
        });
    }

    /**
     * メール文面のプレースホルダを置換する
     */
    private function replacePlaceholders(FormSetting $form_setting, array $request_data, string $body_template): string
    {
        $replace_list = $this->getReplaceList($form_setting, $request_data);

        foreach ($replace_list as $placeholder => $data_value) {
            $body_template = str_replace(
                $placeholder,
                $data_value,
                $body_template
            );
        }

        return $body_template;
    }

    /**
     * 通知メールを送るか確認
     * @param FormSetting $form_setting
     * @return bool
     */
    private function checkNotificationMail(FormSetting $form_setting): bool
    {
        if (is_null($form_setting->mailSetting)) {
            return false;
        }

        // 通知メールを送るか確認
        if ($form_setting->mailSetting->notification_mail_flg === CommonConst::USE_TYPE_DISABLED) {
            return false;
        }

        // 文面が設定されているか確認
        if (empty($form_setting->mailSetting->notification_mail_message)) {
            return false;
        }

        return true;
    }

    /**
     * 自動返信メールを送信するか確認
     * @param FormSetting $form_setting
     * @param array $request_data
     * @return bool
     */
    private function checkAutoReplyMail(FormSetting $form_setting, array $request_data): bool
    {
        if (is_null($form_setting->mailSetting)) {
            return false;
        }

        // 自動返信メールを送信するか確認
        if ($form_setting->mailSetting->auto_reply_mail_flg === CommonConst::USE_TYPE_DISABLED) {
            return false;
        }

        // メールアドレスが入力されているか確認
        if (!isset($request_data['email'])) {
            return false;
        }

        // 文面が設定されているか確認
        if (empty($form_setting->mailSetting->auto_reply_mail_message)) {
            return false;
        }

        return true;
    }

    /**
     * 置換する内容を取得する
     * TODO
     *
     * @param FormSetting $form_setting
     * @param array $request_data
     * @return array
     */
    private function getReplaceList(FormSetting $form_setting, array $request_data): array
    {
        $replace_list = [];

        foreach ($form_setting->formItems as $form_item) {
            $replace_list = array_merge($replace_list, $this->makeReplaceData($form_item, $request_data));
        }

        return $replace_list;
    }
}
