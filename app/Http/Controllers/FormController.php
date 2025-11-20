<?php

namespace App\Http\Controllers;

use App\Http\Requests\Form\RegisterRequest;
use App\Models\FormSetting;
use App\Service\ApplicationsService;
use App\Service\FormSettingService;
use App\Service\MailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class FormController extends Controller
{
    private FormSettingService $form_service;
    private MailService $mail_service;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->form_service = app(FormSettingService::class);
        $this->mail_service = app(MailService::class);
    }

    /**
     * 申込画面を表示する
     * @param string $route_name
     * @return View
     */
    public function index(string $route_name): View
    {
        $form_setting = $this->form_service->getSurveyByRouteName($route_name);
        if (!$this->checkFormAvailablePeriod($form_setting)) {
            return view('form.outside_period',[
                'form_setting' => $form_setting,
            ]);
        }

        return view('form.index',[
            'form_setting' => $form_setting,
        ]);
    }

    /**
     * 申込みデータ登録処理
     * @param string $route_name
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function register(string $route_name, RegisterRequest $request): RedirectResponse
    {
        dd(11111);
        $form_setting = $this->form_service->getSurveyByRouteName($route_name);
        // リレーションされている情報を取得しておく
        $form_setting->load('mailSetting', 'formItems', 'message');

        $request_data = $request->validated();

        // 申込みデータ登録処理
        app(ApplicationsService::class)->register($form_setting, $request_data);

        // 通知メール
        $this->mail_service->sendNotificationMail($form_setting, $request_data);

        // 自動返信メール
        $this->mail_service->sendAutoReplyMail($form_setting, $request_data);

        return redirect()->route('form_complete', ['route_name' => $route_name]);
    }

    /**
     * 申込完了画面を表示する
     * @param string $route_name
     * @return View
     */
    public function complete(string $route_name): View
    {
        $form_setting = $this->form_service->getSurveyByRouteName($route_name);

        return view('form.complete',[
            'form_setting' => $form_setting,
            'message' => $form_setting->message,
        ]);
    }

    /**
     * 申込み可能な期間か確認する
     * @param FormSetting $form_setting
     * @return bool
     */
    private function checkFormAvailablePeriod(FormSetting $form_setting): bool
    {
        $now = Carbon::now();

        if ($form_setting->start_date > $now) {
            return false;
        }

        if ($form_setting->end_date < $now) {
            return false;
        }

        return true;
    }
}
