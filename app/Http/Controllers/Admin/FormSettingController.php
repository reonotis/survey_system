<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormSetting;
use App\Service\FormSettingService;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\SurveyRegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FormSettingController extends Controller
{
    /**
     * Display the survey list page.
     */
    public function index(): View
    {
        return view('admin.form.index');
    }

    /**
     * Get survey data for DataTables via Ajax.
     */
    public function getFormData(Request $request): JsonResponse
    {
        $survey_query = (new FormSettingService())->getFormListQuery();

        return DataTables::of($survey_query)
            ->addColumn('period', function ($form) {
                $start_date = is_null($form->start_date) ? null : $form->start_date->format('Y-m-d H:i');
                $end_date = is_null($form->end_date) ? null : $form->end_date->format('Y-m-d H:i');
                return $start_date . ' ～ ' . $end_date;
            })
            ->addColumn('publication_status_text', function ($form) {
                return FormSetting::PUBLICATION_STATUS_LIST[$form->publication_status];
            })
            ->addColumn('billing_status_text', function ($form) {
                return FormSetting::BILLING_STATUS_LIST[$form->billing_status];
            })

            ->make(true);
    }

    /**
     * アンケート登録画面を表示する
     * @return View
     */
    public function create(): View
    {
        return view('admin.form.create');
    }

    /**
     * フォームの登録処理を行う
     * @param SurveyRegisterRequest $request
     * @return JsonResponse
     */
    public function register(SurveyRegisterRequest $request): JsonResponse
    {
        (new FormSettingService())->create(
            $request->validated(),
            request()->getHost()
        );

        return redirect()->route('admin_form_index')->with('success', 'アンケートを登録しました');
    }

}
