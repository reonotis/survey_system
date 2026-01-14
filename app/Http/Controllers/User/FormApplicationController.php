<?php

namespace App\Http\Controllers\User;

use App\Consts\CommonConst;
use App\Http\Controllers\UserController;
use App\Models\FormItem;
use App\Models\FormSetting;
use App\Service\ApplicationsService;
use App\Service\DisplayFormItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 *
 */
class FormApplicationController extends UserController
{
    /** @var DisplayFormItemService $display_form_item_service */
    private DisplayFormItemService $display_form_item_service;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
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

        $display_columns = $this->buildDisplayColumns($form_setting, $display_item_ids);

        return view('user.form.application', [
            'form_setting' => $form_setting,
            'display_item_ids' => $display_item_ids,
            'display_columns' => $display_columns,
        ]);
    }

    /**
     * Get form data for DataTables via Ajax.
     * @param FormSetting $form_setting
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
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
            ->addColumn('gender', function ($application) {
                return CommonConst::GENDER_LIST[$application->gender]?? '';
            })

            ->addColumn('address', function ($application) {
                return $application->post_code . $application->address;
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

        return redirect()->back()->with('success', ['更新しました。']);
    }

    /**
     * @param FormSetting $form_setting
     * @param array $display_item_ids
     * @return array<int, array<string, string>>
     */
    private function buildDisplayColumns(FormSetting $form_setting, array $display_item_ids): array
    {
        $form_items = $form_setting->formItems->keyBy('id');

        $data = collect($display_item_ids)
            ->map(function ($itemId) use ($form_items) {
                $form_item = $form_items->get($itemId);

                if (!$form_item) {
                    return null;
                }

                $dataKey = $this->resolveColumnKey($form_item->item_type);

                if (!$dataKey) {
                    return [
                        'data' => $form_item->item_type,
                        'name' => $form_item->item_type,
                        'title' => $form_item->item_title ?? FormItem::ITEM_TYPE_LIST[$form_item->item_type],
                        'defaultContent' => '',
                    ];
                }

                $title = $form_item->item_title ?: (FormItem::ITEM_TYPE_LIST[$form_item->item_type] ?? '項目');

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

        return $data;
    }

    /**
     * @param int $item_type
     * @return string|null
     */
    private function resolveColumnKey(int $item_type): ?string
    {
        return match ($item_type) {
            FormItem::ITEM_TYPE_NAME => 'full_name',
            FormItem::ITEM_TYPE_KANA => 'full_kana',
            FormItem::ITEM_TYPE_EMAIL => 'email',
            FormItem::ITEM_TYPE_TEL => 'tel',
            FormItem::ITEM_TYPE_GENDER => 'gender',
            FormItem::ITEM_TYPE_ADDRESS => 'address',
            default => null,
        };
    }
}
