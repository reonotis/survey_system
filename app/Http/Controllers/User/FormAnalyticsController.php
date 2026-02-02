<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Consts\CommonConst;
use App\Http\Controllers\UserController;
use App\Models\FormSetting;
use App\Service\ApplicationsService;
use App\Service\AnalyticsService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 *
 */
class FormAnalyticsController extends UserController
{
    private AnalyticsService $analytics_service;
    private ApplicationsService $application_service;

    /**
     * コンストラクタ
     */
    public function __construct(
        AnalyticsService $analyticsService,
        ApplicationsService $applicationsService
    )    {
        parent::__construct();

        $this->analytics_service = $analyticsService;
        $this->application_service = $applicationsService;
    }

    /**
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        $form_setting->load('formItems');

        // 申込件数を取得
        $total_count = $this->application_service->getApplicationCount($form_setting->id);

        // ウィジェット追加時に選択可能な項目
        $form_items_array = $this->analytics_service->makeSelectableFormItems($form_setting);

        // 各種分析のウィジェット情報を取得
        $analytics_list = $this->analytics_service->getByFormSettingId($form_setting->id, $total_count);

        return view('user.form.analytics', [
            'form_setting' => $form_setting,
            'form_items' => $form_items_array,
            'total_count' => $total_count,
            'analytics_list' => $analytics_list,
            'widget_type_list' => CommonConst::WIDGET_TYPE_LIST,
        ]);
    }

    /**
     * @param FormSetting $form_setting
     * @param Request $request
     * @return JsonResponse
     */
    public function registerWidgetRow(FormSetting $form_setting, Request $request): JsonResponse
    {
        try {
            $row = DB::transaction(function () use ($form_setting, $request) {
                return $this->analytics_service->createDashboardRows($form_setting->id, $request->all())->toArray();
            });

            return response()->json([
                'success' => true,
                'row' => $row,
            ]);
        } catch (Exception $error) {
            Log::error($error->getMessage());
            return response()->json([
                'message' => $error->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    /**
     * @param FormSetting $form_setting
     * @param Request $request
     * @return JsonResponse
     */
    public function widgetRowDelete(FormSetting $form_setting, Request $request): JsonResponse
    {
        try {

            DB::transaction(function () use ($form_setting, $request) {
                $this->analytics_service->dashboardRowDelete($form_setting->id, $request->id);
            });

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $error) {
            Log::error($error->getMessage());
            return response()->json([
                'message' => $error->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    /**
     * @param FormSetting $form_setting
     * @param Request $request
     * @return RedirectResponse
     */
    public function addWidget(FormSetting $form_setting, Request $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($form_setting, $request) {
                // 行データを取得
                $analytics_row =  $this->analytics_service->getAnalyticsRowById((int)$request->row_id);

                // ウィジェットデータを登録
                $analytics_widget = $this->analytics_service->create([
                    'form_setting_id' => $form_setting->id,
                    'form_item_id' => $request->form_item_id,
                    'analytics_title' => $request->analytics_title,
                    'display_type' => $request->graph_type,
                ]);

                // 行データの更新
                $this->analytics_service->updateAnalyticsRow($analytics_row, [
                    'analytics_dashboard_widget_id_' . $request->column_id => $analytics_widget->id
                ]);
            });
            return redirect()->back()->with('success', ['ウィジェットを登録しました']);
        } catch (Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['ウィジェットの登録に失敗しました']);
        }

    }


}
