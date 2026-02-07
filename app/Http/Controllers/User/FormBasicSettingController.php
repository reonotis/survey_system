<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Models\FormSetting;
use App\Service\FormSettingService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Log;

class FormBasicSettingController extends UserController
{
    /**
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        return view('user.form.basic.basic-setting', [
            'form_setting' => $form_setting
        ]);
    }

    /**
     * @param FormSetting $form_setting
     * @param Request $request // TODO
     * @return RedirectResponse
     */
    public function update(FormSetting $form_setting, Request $request): RedirectResponse
    {
        try {
            $service = app(FormSettingService::class);
            $service->update($form_setting, [
                'title' => $request->title,
                'start_date' => $request->start_date ? Carbon::parse($request->start_date)->format('Y/m/d H:i') : null,
                'end_date' => $request->end_date ? Carbon::parse($request->end_date)->format('Y/m/d H:i') : null,
                'publication_status' => $request->publication_status,
                'max_applications' => $request->max_applications,
            ]);
            return redirect()->back()->with('success', ['更新しました']);
        } catch (Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['更新に失敗しました']);
        }
    }

}
