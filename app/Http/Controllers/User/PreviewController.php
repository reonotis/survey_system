<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Models\FormSetting;
use Illuminate\View\View;

class PreviewController extends UserController
{
    /**
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        return view('user.form.preview', [
            'form_setting' => $form_setting,
        ]);
    }
}
