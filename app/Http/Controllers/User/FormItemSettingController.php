<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use App\Models\FormItem;
use App\Models\FormSetting;
use App\Service\ApplicationsService;
use App\Service\FormItemService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class FormItemSettingController extends UserController
{
    /** @var FormItemService $form_item_service */
    public FormItemService $form_item_service;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();

        $this->form_item_service = app(FormItemService::class);
    }

    /**
     * 項目設定画面を表示する
     * @param FormSetting $form_setting
     * @return View
     */
    public function index(FormSetting $form_setting): View
    {
        // 編集中ではない場合
        if ($form_setting->is_draft_item == 0) {
            // 公開中の項目データを取得
            $form_items = $form_setting->formItems;

            // 設定済みの項目レコードが存在する場合は、編集中のテーブルにコピーしておく
            if ($form_items->isNotEmpty()) {
                $this->form_item_service->copyDraftFormItems($form_items, $form_setting->id);
            }

            // 編集中にする
            $this->form_item_service->setEditingStatus($form_setting, 1);
        }

        return view('user.form.item-setting', [
            'form_setting' => $form_setting,
            'draft_form_items' => $form_setting->draftFormItems,
            'all_form_item_list' => FormItem::ITEM_TYPE_LIST, // 項目名の一覧
            'upper_limit_item_type' => FormItem::ITEM_TYPE_UPPER_LIMIT, // 登録できる項目の上限値
        ]);
    }

    /**
     * @param FormSetting $form_setting
     * @return RedirectResponse
     */
    public function allDraftDelete(FormSetting $form_setting): RedirectResponse
    {
        try {
            DB::transaction(function () use ($form_setting) {
                $form_setting->draftFormItems()->delete();
            });

            return redirect()->route('user_form_item_setting', ['form_setting' => $form_setting->id]);
        } catch (Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['削除に失敗しました']);
        }
    }

    /**
     * @param FormSetting $form_setting
     * @return RedirectResponse
     */
    public function resetDraftItem(FormSetting $form_setting): RedirectResponse
    {
        try {
            DB::transaction(function () use ($form_setting) {
                $form_setting->is_draft_item = 0;
                $form_setting->save();

                $form_setting->draftFormItems()->delete();
            });
            return redirect()->route('user_form_item_setting', ['form_setting' => $form_setting->id])->with('success', ['編集内容をリセットしました']);
        } catch (Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['編集内容をリセットに失敗しました']);
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
     * @param Request $request
     * @return JsonResponse
     */
    public function draftAddItem(FormSetting $form_setting, Request $request): JsonResponse
    {
        try {
            $draft_items = $form_setting->draftFormItems;
            $max_sort = $draft_items->max('sort');
            $new_sort = $max_sort + 1;

            $item_type = $request->item_type;

            $form_item_service = app(FormItemService::class);
            $form_item_draft = $form_item_service->addDraft(
                $form_setting->id,
                (int)$item_type,
                $new_sort
            );

            return response()->json([
                'message' => $item_type . 'を追加しました',
                'success' => true,
                'form_item_draft' => $form_item_draft,
            ]);

        } catch (Exception $error) {
            Log::error($error->getMessage());

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
        } catch (Exception $error) {
            Log::error($error->getMessage());

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
        } catch (Exception $error) {
            Log::error($error->getMessage());

            return response()->json([
                'message' => $error->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    public function draftItemDelete(FormSetting $form_setting, Request $request): JsonResponse
    {
        try {
            Log::error($request->all());

            $form_item_service = app(FormItemService::class);
            $form_item_service->deleteDraftItem((int)$request->item_id);

            return response()->json([
                'message' => '並び替え',
                'success' => true,
            ]);
        } catch (Exception $error) {
            Log::error($error->getMessage());

            return response()->json([
                'message' => $error->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    /**
     * @param FormSetting $form_setting
     * @return RedirectResponse
     */
    public function saveFormItems(FormSetting $form_setting): RedirectResponse
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
                        'value_list' => $draft_form_item->value_list,
                        'details' => $draft_form_item->details,
                        'annotation_text' => $draft_form_item->annotation_text,
                        'long_text' => $draft_form_item->long_text,
                        'sort' => $draft_form_item->sort,
                    ];
                    $form_item_service->updateFormItemById($draft_form_item->form_item_id, $param);

                    // 更新済み変数に追加しておく
                    $updated_ids[] = $draft_form_item->form_item_id;
                } else {
                    // 既存の項目が無いので作成
                    $form_item_service->create([
                        'form_setting_id' => $draft_form_item->form_setting_id,
                        'item_type' => $draft_form_item->item_type,
                        'item_title' => $draft_form_item->item_title,
                        'field_required' => $draft_form_item->field_required,
                        'value_list' => $draft_form_item->value_list,
                        'details' => $draft_form_item->details,
                        'annotation_text' => $draft_form_item->annotation_text,
                        'long_text' => $draft_form_item->long_text,
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

            return redirect()->route('user_form_item_setting', ['form_setting' => $form_setting->id])->with('success', ['更新しました']);
        } catch (Exception $error) {
            Log::error($error->getMessage());
            return redirect()->back()->with('error', ['更新に失敗しました']);
        }
    }

}
