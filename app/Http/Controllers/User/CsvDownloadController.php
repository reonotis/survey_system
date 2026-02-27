<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Consts\CommonConst;
use App\Http\Controllers\UserController;
use App\Models\FormSetting;
use App\Service\ApplicationsService;
use App\Service\DisplayFormItemService;
use App\Enums\ItemType;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 *
 */
class CsvDownloadController extends UserController
{
    private ApplicationsService $applications_service;
    private DisplayFormItemService $display_form_item_service;

    /**
     * コンストラクタ
     */
    public function __construct(
        ApplicationsService    $applications_service,
        DisplayFormItemService $display_form_item_service,
    )
    {
        parent::__construct();

        $this->applications_service = $applications_service;
        $this->display_form_item_service = $display_form_item_service;
    }

    /**
     * 応募一覧のCSVダウンロード
     */
    public function download(FormSetting $form_setting): StreamedResponse
    {
        $form_setting->load('formItems');
        $form_items_id_ids = $form_setting->formItems->pluck('id')->all();

        $display_columns = $this->buildDisplayColumns($form_setting, $form_items_id_ids);

        dd($display_columns);

        // 先頭に申込日時の列を追加
        array_unshift($display_columns, [
            'data' => 'created_at_text',
            'name' => 'created_at',
            'title' => '申込日時',
            'defaultContent' => '',
        ]);

        $query = $this->applications_service->getFormListQuery($form_setting->id, []);

        $file_name = sprintf(
            'applications_%d_%s.csv',
            $form_setting->id,
            now()->format('YmdHis')
        );

        $callback = function () use ($query, $display_columns) {
            $handle = fopen('php://output', 'w');

            // BOM（Excel対策）
            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ヘッダー行
            $headers = [];
            foreach ($display_columns as $column) {
                $headers[] = $column['title'] ?? '';
            }
            fputcsv($handle, $headers);

            // データ行
            $query->chunk(500, function ($applications) use ($handle, $display_columns) {
                foreach ($applications as $application) {
                    $row = [];

                    foreach ($display_columns as $column) {
                        $key = $column['data'] ?? '';

                        if ($key === 'created_at_text') {
                            $row[] = optional($application->created_at)->format('Y-m-d H:i:s') ?? '';
                            continue;
                        }

                        if ($key === 'full_name') {
                            $parts = array_filter([$application->name, $application->name_last]);
                            $row[] = trim(implode(' ', $parts));
                            continue;
                        }

                        if ($key === 'full_kana') {
                            $parts = array_filter([$application->kana, $application->kana_last]);
                            $row[] = trim(implode(' ', $parts));
                            continue;
                        }

                        if ($key === 'gender') {
                            $row[] = CommonConst::GENDER_LIST[$application->gender] ?? '';
                            continue;
                        }

                        if ($key === 'address') {
                            $row[] = ($application->post_code ?? '') . ($application->address ?? '');
                            continue;
                        }

                        if (Str::startsWith($key, 'form_item_')) {
                            $form_item_id = (int)str_replace('form_item_', '', $key);
                            $subs = $application->applicationSubs->where('form_item_id', $form_item_id);
                            $row[] = $subs->pluck('answer_text')->filter()->implode(', ');
                            continue;
                        }

                        $row[] = $application->{$key} ?? '';
                    }

                    fputcsv($handle, $row);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload(
            $callback,
            $file_name,
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]
        );
    }

    /**
     * 応募一覧表示と同じ列構成を作成
     *
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

                $dataKey = $this->resolveColumnKey($form_item->item_type->value);

                // ラジオボタン、チェックボックス、セレクトボックスの場合はform_item_{id}をキーとして使用
                if (!$dataKey && in_array($form_item->item_type->value, [ItemType::RADIO->value, ItemType::CHECKBOX->value, ItemType::SELECT_BOX->value])) {
                    $dataKey = 'form_item_' . $form_item->id;
                }

                if (!$dataKey) {
                    return [
                        'data' => $form_item->item_type,
                        'name' => $form_item->item_type,
                        'title' => $form_item->item_title ?? $form_item->item_type->label(),
                        'defaultContent' => '',
                    ];
                }

                $title = $form_item->item_title ?: $form_item->item_type->label();

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
            ItemType::NAME->value => 'full_name',
            ItemType::KANA->value => 'full_kana',
            ItemType::EMAIL->value => 'email',
            ItemType::TEL->value => 'tel',
            ItemType::GENDER->value => 'gender',
            ItemType::ADDRESS->value => 'address',
            default => null,
        };
    }

}
