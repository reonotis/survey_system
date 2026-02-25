<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Http\Requests\Owner\FormSettingRegisterRequest;
use App\Models\FormSetting;
use App\Service\ApplicationsService;
use App\Service\FormSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class FormSettingController extends UserController
{
    private FormSettingService $form_setting_service;

    /**
     * コンストラクタ
     */
    public function __construct(
        FormSettingService $form_setting_service,
    )    {
        parent::__construct();

        $this->form_setting_service = $form_setting_service;
    }

    /**
     * Get form data for DataTables via Ajax.
     */
    public function getFormData(Request $request): JsonResponse
    {
        $form_name = $request->input('form_name');
        $status = $request->input('status', []);

        $form_query = $this->form_setting_service->getFormListQuery(
            $this->my_user->id,
            $form_name,
            $status
        );

        return DataTables::of($form_query)
            ->addColumn('period', function ($form) {
                $start_date = is_null($form->start_date) ? null : $form->start_date->format('Y-m-d H:i');
                $end_date = is_null($form->end_date) ? null : $form->end_date->format('Y-m-d H:i');
                return $start_date . ' ～ ' . $end_date;
            })
            ->addColumn('count', function ($form) {
                return $form->applications_count . '件';
            })
            ->addColumn('publication_status_text', function ($form) {
                return $form->publication_status->label();
            })
            ->addColumn('plan', function ($form) {
                return $form->has_active_subscription ? 'Pro版' : '無料版';
            })
            ->make(true);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('user.form.create', [
        ]);
    }

    /**
     * @param FormSettingRegisterRequest $request
     * @return RedirectResponse
     */
    public function store(FormSettingRegisterRequest $request): RedirectResponse
    {
        try {
            $param = $request->validated();
            $param['created_by_user'] = $this->my_user->id;
            $form_setting = $this->form_setting_service->create(
                $param,
                request()->getHost()
            );
            return redirect()->route('user_form_basic_setting', ['form_setting' => $form_setting->id])->with('success', ['新しいフォームを作成しました。引き続き詳細設定を行って下さい']);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['新しいフォームの作成に失敗しました']);
        }
    }

    /**
     * @param FormSetting $form_setting
     * @return RedirectResponse
     */
    public function delete(FormSetting $form_setting): RedirectResponse
    {
        try {
            $this->form_setting_service->delete($form_setting);
            return redirect()->back()->with('success', ['フォームを削除しました。']);
        } catch (\Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['フォームの削除に失敗しました']);
        }
    }


}
