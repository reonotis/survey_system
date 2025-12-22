<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Http\Requests\Owner\UpdateFormItemRequest;
use App\Http\Requests\Owner\UpdateFormItemReactRequest;
use App\Models\FormItem;
use App\Models\FormSetting;
use App\Service\FormItemService;
use App\Service\FormMailSettingService;
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
        // 編集中のデータを取得
        if ($form_setting->is_draft_item == 0) {

            // 公開中の項目データを取得
            $form_items = $form_setting->formItems;

            if ($form_items->isNotEmpty()) {
                // 既存のデータを編集中のテーブルに保存する
                $form_item_service = app(FormItemService::class);
                $records = [];
                foreach ($form_items as $form_item) {
                    $record = [
                        'form_setting_id' => $form_setting->id,
                        'form_item_id' => $form_item->id,
                        'item_type' => $form_item->item_type,
                        'field_required' => $form_item->field_required,
                        'item_title' => $form_item->item_title,
                        'value_list' => $form_item->value_list,
                        'details' => $form_item->details,
                        'annotation_text' => $form_item->annotation_text,
                        'sort' => $form_item->sort,
                    ];
                    $records[] = $record;
                }
                $form_item_service->insertDraft($records);

            }

            $form_setting->is_draft_item = 1;
            $form_setting->save();
        }

        $draft_form_items = $form_setting->draftFormItems;

        return view('user.form.item-setting', [
            'form_setting' => $form_setting,
            'draft_form_items' => $draft_form_items,
            'all_form_item_list' => FormItem::ITEM_TYPE_LIST, // 項目名の一覧
            'upper_limit_item_type' => FormItem::ITEM_TYPE_UPPER_LIMIT, // 登録できる項目の上限値
        ]);
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
     * @return JsonResponse
     */
    public function draftAddItem(FormSetting $form_setting, Request $request): JsonResponse
    {
        try {
            $item_type = $request->item_type;

            $form_item_service = app(FormItemService::class);
            $form_item_draft = $form_item_service->addDraft(
                 $form_setting->id,
                (int)$request->item_type,
            );

            return response()->json([
                'message' => $item_type . 'を追加しました',
                'success' => true,
                'form_item_draft' => $form_item_draft,
            ]);

        } catch (\Exception $error) {
            \Log::error($error->getMessage());

            return response()->json([
                'message' => $error->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    public function draftSortChange(FormSetting $form_setting, Request $request): JsonResponse
    {
        try {
            $form_item_service = app(FormItemService::class);

            $sort = 1;
            foreach ($request->item_ids as $form_item_drafts_id) {
                $form_item_service->sortChangeDraft((int)$form_item_drafts_id, $sort);
                $sort++;
            }

            return response()->json([
                'message' => '並び替え完了',
                'success' => true,
            ]);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());

            return response()->json([
                'message' => $error->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    public function draftItemSave(FormSetting $form_setting, Request $request): JsonResponse
    {
        try {
            $form_item_service = app(FormItemService::class);
            $form_item_service->updateByFormItem($request->item_id, $request->key, $request->value);

            return response()->json([
                'message' => '更新完了',
                'success' => true,
            ]);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());

            return response()->json([
                'message' => $error->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    public function draftItemDelete(FormSetting $form_setting, Request $request): JsonResponse
    {
        try {
            \Log::error($request->all());

            $form_item_service = app(FormItemService::class);
            $form_item_service->deleteDraftItem((int)$request->item_id);

            return response()->json([
                'message' => '並び替え',
                'success' => true,
            ]);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());

            return response()->json([
                'message' => $error->getMessage(),
                'success' => false,
            ], 500);
        }
    }


    public function saveFormItems(FormSetting $form_setting)
    {
        $form_item_service = app(FormItemService::class);
        try {
            // 編集中の項目を取得
            $draft_form_items = $form_setting->draftFormItems;

            // 公開済みの項目を取得
            $form_items = $form_setting->formItems;
            $updated_ids = [];

            // 編集中の項目をループ
            foreach ($draft_form_items as $draft_form_item) {
                // 既存の項目がある場合は更新
                if ($draft_form_item->form_item_id) {
                    $param = [
                        'form_setting_id' => $draft_form_item->form_setting_id,
                        'item_type' => $draft_form_item->item_type,
                        'item_title' => $draft_form_item->item_title,
                        'field_required' => $draft_form_item->field_required,
                        'details' => $draft_form_item->details,
                        'annotation_text' => $draft_form_item->annotation_text,
                        'sort' => $draft_form_item->sort,
                    ];
                    $form_item_service->updateFormItemById($draft_form_item->form_item_id, $param);

                    // 更新済み変数に追加しておく
                    $updated_ids[]  = $draft_form_item->form_item_id;
                } else {
                    // 既存の項目が無いので作成
                    $form_item_service->create([
                        'form_setting_id' => $draft_form_item->form_setting_id,
                        'item_type' => $draft_form_item->item_type,
                        'item_title' => $draft_form_item->item_title,
                        'field_required' => $draft_form_item->field_required,
                        'details' => $draft_form_item->details,
                        'annotation_text' => $draft_form_item->annotation_text,
                        'sort' => $draft_form_item->sort,
                    ]);
                }
            }

            // 更新していないデータは削除する
            $form_items
                ->whereNotIn('id', $updated_ids)
                ->each(function ($form_item) {
                    $form_item->delete();
                });

            $form_setting->is_draft_item = 0;
            $form_setting->save();

            $form_setting->draftFormItems()->delete();

            return redirect()->route('user_form_item_setting',  ['form_setting' => $form_setting->id])->with('success',['更新しました']);
        } catch (\Exception $error) {
            \Log::error($error->getMessage());
            return redirect()->back()->with('error', ['更新に失敗しました']);
        }
    }

}
