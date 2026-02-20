<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ItemType;
use App\Models\FormItem;
use App\Consts\CommonConst;
use Illuminate\Database\Seeder;

class FormItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $records = [
            [
                'form_setting_id' => 1,
                'item_type' => ItemType::NAME->value,
                'field_required' => CommonConst::FLG_ON,
                'item_title' => 'お名前を入力して！',
                'value_list' => null,
                'details' => [
                    'name_separate_type' => CommonConst::NAME_SEPARATE,
                ],
            ], [
                'form_setting_id' => 1,
                'item_type' => ItemType::CHECKBOX->value,
                'field_required' => CommonConst::FLG_ON,
                'item_title' => '好きなおかし',
                'value_list' => [
                    [
                        'name' => 'プレミアムチョコレート',
                        'count' => null
                    ], [
                        'name' => 'ホワイトチョコレート',
                        'count' => null
                    ], [
                        'name' => 'クッキー',
                        'count' => null
                    ], [
                        'name' => 'ロールケーキ',
                        'count' => null
                    ],
                ],
                'details' => [
                    'max_count' => 1,
                ],
                'annotation_text' => null,
                'long_text' => null,
            ]

        ];


        foreach ($records as $item) {
            FormItem::create($item);
        }
    }
}

