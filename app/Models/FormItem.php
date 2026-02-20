<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $form_setting_id
 * @property int|null $form_item_id
 * @property ItemType $item_type
 * @property bool $field_required
 * @property string|null $item_title
 * @property array|null $value_list
 * @property array|null $details
 * @property string|null $annotation_text
 * @property string|null $long_text
 * @property int $sort
 */
class FormItem extends Model
{
    use HasFactory, SoftDeletes;

    // 項目名の一覧
    const ITEM_TYPE_LIST = [
        ItemType::NAME->value => 'お名前',
        ItemType::KANA->value => 'ヨミ',
        ItemType::EMAIL->value => 'メールアドレス',
        ItemType::TEL->value => '電話番号',
        ItemType::GENDER->value => '性別',
        ItemType::ADDRESS->value => '住所',
//        ItemType::TEXT->value => 'テキスト（短文）',
//        ItemType::TEXTAREA->value => 'テキスト（長文）',
        ItemType::CHECKBOX->value => 'チェックボックス',
        ItemType::RADIO->value => 'ラジオボタン',
        ItemType::SELECT_BOX->value => 'セレクトボックス',
        ItemType::TERMS->value => '利用規約',
        ItemType::PRECAUTIONS->value => '注意事項',
    ];

    // 登録できる項目の上限値
    const ITEM_TYPE_UPPER_LIMIT = [
        ItemType::NAME->value => 1,
        ItemType::KANA->value => 1,
        ItemType::EMAIL->value => 1,
        ItemType::TEL->value => 1,
        ItemType::GENDER->value => 1,
        ItemType::ADDRESS->value => 1,
        ItemType::CHECKBOX->value => 5,
        ItemType::RADIO->value => 5,
        ItemType::TERMS->value => 1,
        ItemType::PRECAUTIONS->value => 1,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_setting_id',
        'item_type',
        'field_required',
        'item_title',
        'value_list',
        'details',
        'annotation_text',
        'long_text',
        'sort',
    ];

    protected $casts = [
        'value_list' => 'array',
        'details' => 'array',
        'item_type' => ItemType::class,
    ];

}
