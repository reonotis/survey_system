<?php

namespace App\Http\Controllers\User;

use App\Consts\CommonConst;
use App\Http\Controllers\UserController;
use App\Models\FormItem;
use App\Models\FormSetting;
use App\Service\ApplicationsService;
use App\Service\DisplayFormItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 *
 */
class FormAnalyticsController extends UserController
{

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        $application_service = new ApplicationsService();

        return view('user.form.analytics', [
            'form_setting' => $form_setting,
            'total_count' => $application_service->getApplications($form_setting->id),
        ]);
    }

}
