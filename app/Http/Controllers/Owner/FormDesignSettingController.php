<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OwnerController;
use App\Http\Requests\Owner\UpdateFormItemRequest;
use App\Models\FormSetting;
use App\Models\FormItem;
use App\Service\FormSettingService;
use App\Service\FormItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormDesignSettingController extends OwnerController
{
    /**
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        return view('owner.form.design-setting', [
            'form_setting' => $form_setting,
        ]);
    }
}
