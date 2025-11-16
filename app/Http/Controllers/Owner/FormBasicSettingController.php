<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\FormSetting;
use App\Service\FormSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class FormBasicSettingController extends Controller
{
    /**
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        return view('owner.form.basic-setting', [
            'form_setting' => $form_setting
        ]);
    }

    /**
     * @param FormSetting $form_setting
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(FormSetting $form_setting, Request $request): RedirectResponse
    {

        $service = app(FormSettingService::class);
        $service->update($form_setting, [
            'title' => $request->title,
            'start_date' => $request->start_date ? Carbon::parse($request->start_date)->format('Y/m/d H:i') : null,
            'end_date' => $request->end_date ? Carbon::parse($request->end_date)->format('Y/m/d H:i') : null,
        ]);


        return redirect()->back()->with('success', '更新しました。');
    }


}
