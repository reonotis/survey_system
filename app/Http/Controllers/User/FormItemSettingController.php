<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Http\Requests\Owner\UpdateFormItemRequest;
use App\Http\Requests\Owner\UpdateFormItemReactRequest;
use App\Models\FormItem;
use App\Models\FormSetting;
use App\Service\FormItemService;
use App\Service\FormSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormItemSettingController extends UserController
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

        return view('user.form.item-setting', [
            'form_setting' => $form_setting,
            'form_items' => $form_items,
            'selectable_item_list' => $selectable_item_list,
        ]);
    }

    /**
     * @param FormSetting $form_setting
     * @param Request $request TODO
     * @return JsonResponse
     */
    public function registerFormItem(FormSetting $form_setting, Request $request): JsonResponse
    {
        try {
            $insert_sort_no = $request->insert_index + 1;
            $form_setting->load('formItems');

            // 既存のレコードのソートを更新する
            $items_to_update = $form_setting->formItems->where('sort', '>=', $insert_sort_no);

            // 降順で更新することで、同じ値になることを防ぐ
            foreach ($items_to_update as $item) {
                $item->update(['sort' => $item->sort + 1]);
            }

            $form_item_service = app(FormItemService::class);
            $new_form_item = $form_item_service->create($form_setting, $request->all());

            // 新しく作成された項目をリロードして、関連データも含める
            $new_form_item->refresh();

            return response()->json([
                'message' => '登録しました',
                'success' => true,
                'form_item' => [
                    'id' => $new_form_item->id,
                    'item_type' => $new_form_item->item_type,
                    'item_title' => $new_form_item->item_title,
                    'sort' => $new_form_item->sort,
                    'field_required' => $new_form_item->field_required,
                    'details' => $new_form_item->details,
                    'value_list' => $new_form_item->value_list,
                    'annotation_text' => $new_form_item->annotation_text,
                ],
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'message' => $error->getMessage(),
                'success' => false,
            ]);
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
     * @return RedirectResponse|JsonResponse
     */
    public function updateFormItem(FormSetting $form_setting, FormItem $form_item, UpdateFormItemRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $form_item_service = app(FormItemService::class);
            $form_item_service->update($form_item, $form_item->item_type, $request->validated());

            // React などからの AJAX 要求の場合は JSON を返す
            if ($request->ajax() || $request->wantsJson()) {
                $form_item->refresh();
                $details = json_decode($form_item->details ?? '{}', true);

                return response()->json([
                    'message'   => '更新しました',
                    'success'   => true,
                    'form_item' => [
                        'id'              => $form_item->id,
                        'item_type'       => $form_item->item_type,
                        'item_title'      => $form_item->item_title,
                        'sort'            => $form_item->sort,
                        'field_required'  => (bool)$form_item->field_required,
                        'details'         => $details,
                        'value_list'      => $form_item->value_list,
                        'annotation_text' => $form_item->annotation_text,
                    ],
                ]);
            }

            return redirect()->back()->with('success', ['更新しました']);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => $error->getMessage(),
                    'success' => false,
                ], 500);
            }

            return redirect()->back()->with('error', ['更新に失敗しました']);
        }
    }

    /**
     * @param FormSetting $form_setting
     * @param FormItem $form_item
     * @param UpdateFormItemReactRequest $request
     * @return RedirectResponse|JsonResponse
     */
    public function updateFormItemReact(FormSetting $form_setting, FormItem $form_item, UpdateFormItemReactRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $form_item_service = app(FormItemService::class);

            \Log::info($request->target_key . '   ' . $request->target_value);
            $form_item_service->updateByFormItem($form_item,  $request->target_key, $request->target_value);

            // React などからの AJAX 要求の場合は JSON を返す
            if ($request->ajax() || $request->wantsJson()) {
                $form_item->refresh();
                $details = json_decode($form_item->details ?? '{}', true);

                return response()->json([
                    'message'   => '更新しました',
                    'success'   => true,
                    'form_item' => [
                        'id'              => $form_item->id,
                        'item_type'       => $form_item->item_type,
                        'item_title'      => $form_item->item_title,
                        'sort'            => $form_item->sort,
                        'field_required'  => (bool)$form_item->field_required,
                        'details'         => $details,
                        'value_list'      => $form_item->value_list,
                        'annotation_text' => $form_item->annotation_text,
                    ],
                ]);
            }

            return redirect()->back()->with('success', ['更新しました']);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => $error->getMessage(),
                    'success' => false,
                ], 500);
            }

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
        try {
            $form_item->delete();

            return redirect()->back()->with('success', ['項目を削除しました']);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());
            return redirect()->back()->with('error', ['項目の削除に失敗しました']);
        }
    }
}
