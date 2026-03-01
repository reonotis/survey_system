<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Models\MailTemplate;
use App\Service\MailTemplateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class MailTemplateController extends UserController
{
    private MailTemplateService $mail_template_service;

    /**
     * コンストラクタ
     * @param MailTemplateService $mail_template_service
     */
    public function __construct(MailTemplateService $mail_template_service)
    {
        parent::__construct();
        $this->mail_template_service = $mail_template_service;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('user.mail_template.list', [
        ]);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function upsert(Request $request): View
    {
        $id = $request->query('template_id');
        $template = $this->mail_template_service->getById((int)$id);

        return view('user.mail_template.upsert', [
            'template' => $template
        ]);
    }

    /**
     */
    public function getMailTemplateList(Request $request)
    {

        $form_query = $this->mail_template_service->getFormListQuery(
            $this->my_user->id,
        );

        return DataTables::of($form_query)
            ->make(true);
    }


    public function store(Request $request): RedirectResponse
    {
        try {
            $template_id = $request->id;
            $template = $this->mail_template_service->getById((int)$template_id);

            if ($template) {
                $this->mail_template_service->update($template,[
                    'template_name' => $request->template_name,
                    'subject' => $request->subject,
                    'body' => $request->body,
                ]);
            } else {
                $template = MailTemplate::create([
                    'template_name' => $request->template_name,
                    'subject' => $request->subject,
                    'body' => $request->body,
                    'created_by' => $this->my_user->id,
                ]);
            }

            return redirect()->route('user_mail_template_upsert', ['template_id' => $template->id])->with('success', ['更新しました']);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['更新に失敗しました。']);
        }

    }

}
