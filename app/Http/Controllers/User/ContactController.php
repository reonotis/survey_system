<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Consts\MailConst;
use App\Http\Controllers\UserController;
use App\Http\Requests\User\Contact\ContactSendRequest;
use App\Service\MailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 *
 */
class ContactController extends UserController
{
    private MailService $mail_service;

    /**
     * コンストラクタ
     */
    public function __construct(
        MailService $mail_service,
    )    {
        parent::__construct();

        $this->mail_service = $mail_service;
    }

    /**
     * 問い合わせ画面を表示する
     * @return View
     */
    public function index(): View
    {
        return view('user.contact', []);
    }

    /**
     * 問い合わせ内容のメールを送信する
     * @param ContactSendRequest $request
     * @return RedirectResponse
     */
    public function send(ContactSendRequest $request): RedirectResponse
    {
        try {
            $this->mail_service->sendMail(MailConst::CONTACT, $request->validated());

            return redirect()->back()->with('success', ['問い合わせを送信しました。']);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['問い合わせの送信に失敗しました。']);
        }
    }

}
