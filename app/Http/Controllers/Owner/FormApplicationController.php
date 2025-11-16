<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\FormItem;
use App\Models\FormSetting;
use App\Service\ApplicationsService;
use App\Service\DisplayFormItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FormApplicationController extends Controller
{
    /** @var DisplayFormItemService $display_form_item_service */
    private DisplayFormItemService $display_form_item_service;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->display_form_item_service = app(DisplayFormItemService::class);
    }

    /**
     * @param FormSetting $form_setting
     * @return View
     */
    public function show(FormSetting $form_setting): View
    {
        $form_setting->load('formItems');

        $display_item = $this->display_form_item_service->getByFormSettingId($form_setting->id);
        $display_item_ids = $display_item->pluck('form_items_id')->all();

        return view('owner.form.application', [
            'form_setting' => $form_setting,
            'display_item_ids' => $display_item_ids,
            'display_columns' => $this->buildDisplayColumns($form_setting, $display_item_ids),
        ]);
    }

    /**
     * Get form data for DataTables via Ajax.
     */
    public function getApplicationData(FormSetting $form_setting, Request $request): JsonResponse
    {
        $application_query = (new ApplicationsService())->getFormListQuery($form_setting->id, $request->all());

        return DataTables::of($application_query)
            ->addColumn('created_at_text', function ($application) {
                return $application->created_at->format('Y年m月d日 H:i');
            })
            ->addColumn('full_name', function ($application) {
                $parts = array_filter([$application->name, $application->name_last]);
                return trim(implode(' ', $parts));
            })
            ->addColumn('full_kana', function ($application) {
                $parts = array_filter([$application->kana, $application->kana_last]);
                return trim(implode(' ', $parts));
            })
            ->addColumn('tel', function ($application) {
                return $application->tel;
            })
            ->make(true);
    }

    /**
     */
    public function displayItemsUpdate(FormSetting $form_setting, Request $request)
    {
        // 一回削除
        $this->display_form_item_service->deleteByFormSettingId($form_setting->id);

        $record = [];
        foreach ($request->display_items as $display_item) {
            $record[] = [
                'form_setting_id' => $form_setting->id,
                'form_items_id' => $display_item,
            ];

        }

        // 登録
        $this->display_form_item_service->insert($record);

        return redirect()->back()->with('success', '更新しました。');
    }

    /**
     * @param FormSetting $form_setting
     * @param array $display_item_ids
     * @return array<int, array<string, string>>
     */
    private function buildDisplayColumns(FormSetting $form_setting, array $display_item_ids): array
    {
        $formItems = $form_setting->formItems->keyBy('id');

        return collect($display_item_ids)
            ->map(function ($itemId) use ($formItems) {
                $formItem = $formItems->get($itemId);

                if (!$formItem) {
                    return null;
                }

                $dataKey = $this->resolveColumnKey($formItem->item_type);

                if (!$dataKey) {
                    return null;
                }

                $title = $formItem->item_title ?: (FormItem::ITEM_TYPE_LIST[$formItem->item_type] ?? '項目');

                return [
                    'data' => $dataKey,
                    'name' => $dataKey,
                    'title' => $title,
                    'defaultContent' => '',
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param int $itemType
     * @return string|null
     */
    private function resolveColumnKey(int $itemType): ?string
    {
        return match ($itemType) {
            FormItem::ITEM_TYPE_NAME => 'full_name',
            FormItem::ITEM_TYPE_KANA => 'full_kana',
            FormItem::ITEM_TYPE_EMAIL => 'email',
            FormItem::ITEM_TYPE_TEL => 'tel',
            default => null,
        };
    }
}
