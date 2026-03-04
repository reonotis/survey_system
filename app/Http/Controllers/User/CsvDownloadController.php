<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Consts\CommonConst;
use App\Enums\ItemType;
use App\Http\Controllers\UserController;
use App\Models\Application;
use App\Models\FormSetting;
use App\Service\ApplicationsService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 *
 */
class CsvDownloadController extends UserController
{
    private ApplicationsService $applications_service;

    /**
     * コンストラクタ
     */
    public function __construct(ApplicationsService $applications_service)
    {
        parent::__construct();

        $this->applications_service = $applications_service;
    }

    /**
     * 応募一覧のCSVダウンロード
     * @param FormSetting $form_setting
     * @return StreamedResponse
     */
    public function download(FormSetting $form_setting): StreamedResponse
    {
        $display_columns = $this->buildDisplayColumns($form_setting);

        $query = $this->applications_service->getFormListQuery($form_setting->id, []);
        $callback = function () use ($query, $display_columns) {
            $handle = fopen('php://output', 'w');

            // BOM（Excel対策）
            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ヘッダー行
            $headers[] = '申込日時';
            foreach ($display_columns as $column) {
                $headers[] = $column['title'] ?? '';
            }
            fputcsv($handle, $headers);

            // データ行
            $query->chunk(500, function ($applications) use ($handle, $display_columns) {
                // 申込レコードをループ
                foreach ($applications as $application) {
                    $row = [];
                    // 1列目は申込日時
                    $row[] = $application->created_at->format('Y-m-d H:i:s');

                    // 2列目以降の項目作成
                    foreach ($display_columns as $column) {
                        $row[] = match ($column['item_type']) {
                            ItemType::NAME->value => $this->makeName($application),
                            ItemType::KANA->value => $this->makeKana($application),
                            ItemType::EMAIL->value => $application->email,
                            ItemType::GENDER->value => CommonConst::GENDER_LIST[$application->gender] ?? '',
                            ItemType::ADDRESS->value => ($application->post_code ?? '') . ($application->address ?? ''),

                            ItemType::RADIO->value,
                            ItemType::CHECKBOX->value,
                            ItemType::SELECT_BOX->value => $this->makeSelectValue($application, $column['form_item_id']),
                        };
                    }
                    fputcsv($handle, $row);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload(
            $callback,
            sprintf('%s_%s.csv', $form_setting->form_name, now()->format('YmdHis')), // ファイル名
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]
        );
    }

    /**
     * 列構成を作成
     * @param FormSetting $form_setting
     * @return array
     */
    private function buildDisplayColumns(FormSetting $form_setting): array
    {
        $column_data = [];
        foreach ($form_setting->formItems as $form_item) {
            $column_data[] = [
                'form_item_id' => $form_item->id,
                'item_type' => $form_item->item_type->value,
                'title' => $form_item->item_title ?: $form_item->item_type->label(),
            ];
        }
        return $column_data;
    }

    /**
     * 名前列の値を作成する
     * @param Application $application
     * @return string
     */
    private function makeName(Application $application): string
    {
        $parts = array_filter([$application->name, $application->name_last]);
        return trim(implode(' ', $parts));
    }

    /**
     * ヨミ列の値を作成する
     * @param Application $application
     * @return string
     */
    private function makeKana(Application $application): string
    {
        $parts = array_filter([$application->kana, $application->kana_last]);
        return trim(implode(' ', $parts));
    }

    /**
     * 選択肢の値を作成する
     * @param Application $application
     * @param int $form_item_id
     * @return string
     */
    private function makeSelectValue(Application $application, int $form_item_id): string
    {
        $subs = $application->applicationSubs->where('form_item_id', $form_item_id);
        return $subs->pluck('answer_text')->filter()->implode(', ');
    }

}
