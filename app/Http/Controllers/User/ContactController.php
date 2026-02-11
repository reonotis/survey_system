<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Consts\CommonConst;
use App\Http\Controllers\UserController;
use App\Service\ContactService;
use App\Service\MailService;
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
class ContactController extends UserController
{
    private ContactService $contact_service;
    private MailService $mail_service;

    /**
     * コンストラクタ
     */
    public function __construct(
        MailService $mail_service,
        ContactService $contact_service,
    )    {
        parent::__construct();

        $this->mail_service = $mail_service;
        $this->contact_service = $contact_service;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('user.contact', [
            'widget_type_list' => CommonConst::WIDGET_TYPE_LIST,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function send(Request $request): RedirectResponse
    {
        try {
            $this->mail_service->sendContactMail($request->all());

            return redirect()->back()->with('success', ['問い合わせを送信しました。']);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());
            return redirect()->back()->with('error', ['問い合わせの送信に失敗しました。']);
        }
    }

}
