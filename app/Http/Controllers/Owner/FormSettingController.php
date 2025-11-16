<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\FormSetting;
use App\Service\FormSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FormSettingController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('owner.form.list', [
        ]);
    }

    /**
     * @param FormSetting $form
     * @return View
     */
    public function show(FormSetting $form_setting): View
    {
        return view('owner.form.show', [
            'form_setting' => $form_setting
        ]);
    }

    /**
     * Get form data for DataTables via Ajax.
     */
    public function getFormData(Request $request): JsonResponse
    {
        $form_query = (new FormSettingService())->getFormListQuery();

        return DataTables::of($form_query)
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

}
