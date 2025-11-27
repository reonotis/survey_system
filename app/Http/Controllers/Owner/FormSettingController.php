<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\FormSettingRegisterRequest;
use App\Models\FormItem;
use App\Models\FormSetting;
use App\Service\FormSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FormSettingController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('owner.form.list', [
        ]);
    }

    /**
     * Get form data for DataTables via Ajax.
     */
    public function getFormData(Request $request): JsonResponse
    {
        $form_query = (new FormSettingService())->getFormListQuery($this->my_owner->id);

        return DataTables::of($form_query)
            ->addColumn('period', function ($form) {
                $start_date = is_null($form->start_date) ? null : $form->start_date->format('Y-m-d H:i');
                $end_date = is_null($form->end_date) ? null : $form->end_date->format('Y-m-d H:i');
                return $start_date . ' ～ ' . $end_date;
            })
            ->addColumn('count', function ($form) {
                return '-';
            })
            ->make(true);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('owner.form.create', [
        ]);
    }

    /**
     * @return RedirectResponse
     */
    public function store(FormSettingRegisterRequest $request): RedirectResponse
    {
        try {
            $param = $request->validated();
            $param['created_by_owner'] = $this->my_owner->id;
            $form_setting =  (new FormSettingService())->create(
                $param,
                request()->getHost()
            );
            return redirect()->route('owner_form_application_list', ['form_setting' => $form_setting->id])->with('success', ['新しいフォームを作成しました。引き続き詳細設定を行って下さい']);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());
            return redirect()->back()->with('error', ['新しいフォームの作成に失敗しました']);
        }
    }


}
