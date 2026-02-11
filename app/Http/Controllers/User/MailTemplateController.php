<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Models\FormSetting;
use App\Service\FormMailSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MailTemplateController extends UserController
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('user.mail-template', [
        ]);
    }

}
