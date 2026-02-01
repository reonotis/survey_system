<?php

namespace App\Http\Controllers;

use App\Http\Requests\Form\RegisterRequest;
use App\Models\FormSetting;
use App\Service\ApplicationsService;
use App\Service\FormSettingService;
use App\Service\MailService;
use Illuminate\Support\Facades\DB;
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
        parent::__construct();
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
        if (!$form_setting) {
            abort(404);
        }

        // 申込できない場合はエラー画面に遷移する
        $error_type = $this->checkFormAvailablePeriod($form_setting);
        if ($error_type !== 0) {
            return view('form.outside_period', [
                'form_setting' => $form_setting,
                'error_type' => $error_type,
            ]);
        }

        // 選択肢の最大値が設定されている場合は、最大数に達していないかチェックする
        $max_count = $this->checkSelectMaxCount($form_setting);

        return view('form.index', [
            'form_setting' => $form_setting,
            'max_count' => $max_count,
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
        $form_setting = $this->form_service->getSurveyByRouteName($route_name);
        // リレーションされている情報を取得しておく
        $form_setting->load('mailSetting', 'formItems', 'message');

        $request_data = $request->validated();

        DB::transaction(function () use ($form_setting, $request_data) {
            // 申込みデータ登録
            app(ApplicationsService::class)->register($form_setting, $request_data);

            // 通知メール
            $this->mail_service->sendNotificationMail($form_setting, $request_data);

            // 自動返信メール
            $this->mail_service->sendAutoReplyMail($form_setting, $request_data);
        });

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

        return view('form.complete', [
            'form_setting' => $form_setting,
            'message' => $form_setting->message,
        ]);
    }

    /**
     * 申込み可能な期間か確認する
     * @param FormSetting $form_setting
     * @return int
     */
    private function checkFormAvailablePeriod(FormSetting $form_setting): int
    {
        // 申込期間外の場合
        $now = Carbon::now();
        if ($form_setting->start_date > $now) {
            return 1;
        }
        if ($form_setting->end_date < $now) {
            return 1;
        }

        // 非公開の場合
        if ($form_setting->publication_status === FormSetting::PUBLICATION_STATUS_DISABLE) {
            return 2;
        }

        // 申込上限に達している場合
        if ($form_setting->max_applications) {
            $count = app(ApplicationsService::class)->getApplicationCount($form_setting->id);
            if ($form_setting->max_applications <= $count) {
                return 3;
            }
        }

        return 0;
    }


    /**
     */
    private function checkSelectMaxCount(FormSetting $form_setting): array
    {
        if (!$this->checkMaxSetting($form_setting)) {
            return [];
        }

        return app(ApplicationsService::class)->getSelectMaxCount($form_setting->id);
    }

    /**
     * @param FormSetting $form_setting
     * @return bool
     */
    private function checkMaxSetting(FormSetting $form_setting): bool
    {
        foreach ($form_setting->formItems as $form_items) {
            // value_listが設定されてい無い場合は、スルーして次の項目を確認させる
            if (is_null($form_items->value_list)) {
                continue;
            }

            foreach ($form_items->value_list as $value_item) {
                if (is_null($value_item['count'])) {
                    return true;
                }
            }
        }

        return false;
    }
}
