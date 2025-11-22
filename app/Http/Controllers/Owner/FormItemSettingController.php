<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\UpdateFormItemRequest;
use App\Models\FormSetting;
use App\Models\FormItem;
use App\Service\FormSettingService;
use App\Service\FormItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormItemSettingController extends Controller
{
    /**
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        // 設定されている項目を取得
        $form_items = $form_setting->formItems;

        $form_service = app(FormSettingService::class);
        $selectable_item_list = $form_service->getSelectableItemList($form_items);

        return view('owner.form.item-setting', [
            'form_setting' => $form_setting,
            'form_items' => $form_items,
            'selectable_item_list' => $selectable_item_list,
        ]);
    }

    /**
     * @param FormSetting $form_setting
     * @param Request $request TODO
     * @return RedirectResponse
     */
    public function registerFormItem(FormSetting $form_setting, Request $request): RedirectResponse
    {
        try {
            $form_item_service = app(FormItemService::class);
            $form_item_service->create($form_setting, $request->all());

            return redirect()->back()->with('success', ['登録しました']);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());
            return redirect()->back()->with('error', ['失敗しました']);
        }
    }

    /**
     * 項目の並び順を更新
     * @param FormSetting $form_setting
     * @param Request $request
     * @return JsonResponse
     */
    public function updateItemOrder(FormSetting $form_setting, Request $request): JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:form_items,id',
        ]);

        $order = $request->input('order');

        // 順序を更新 TODO
        $sort = 1;
        foreach ($order as $item_id) {
            FormItem::where('id', $item_id)
                ->where('form_setting_id', $form_setting->id)
                ->update(['sort' => $sort]);
            $sort++;
        }

        return response()->json([
            'message' => '順序が更新されました',
            'success' => true,
        ]);
    }

    /**
     * @param FormSetting $form_setting
     * @param FormItem $form_item
     * @param UpdateFormItemRequest $request
     * @return RedirectResponse
     */
    public function updateFormItem(FormSetting $form_setting, FormItem $form_item, UpdateFormItemRequest $request): RedirectResponse
    {
        try {
            $form_item_service = app(FormItemService::class);
            $form_item_service->update($form_item, $form_item->item_type, $request->validated());

            return redirect()->back()->with('success', ['更新しました']);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());
            return redirect()->back()->with('error', ['更新に失敗しました']);
        }
    }

    /**
     * @param FormSetting $form_setting
     * @param FormItem $form_item
     * @return RedirectResponse
     */
    public function deleteFormItem(FormSetting $form_setting, FormItem $form_item): RedirectResponse
    {
        $form_item->delete();
        return redirect()->back();
    }
}
