<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $form_setting_id
 * @property int|null $form_item_id
 * @property int $item_type
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

    const ITEM_TYPE_NAME = 1;
    const ITEM_TYPE_KANA = 2;
    const ITEM_TYPE_EMAIL = 3;
    const ITEM_TYPE_TEL = 4;
    const ITEM_TYPE_GENDER = 5;
    const ITEM_TYPE_ADDRESS = 6;
    const ITEM_TYPE_TEXT = 31;
    const ITEM_TYPE_TEXTAREA = 32;
    const ITEM_TYPE_CHECKBOX = 34;
    const ITEM_TYPE_RADIO = 35;
    const ITEM_TYPE_SELECT_BOX = 36;
    const ITEM_TYPE_TERMS = 51;
    const ITEM_TYPE_PRECAUTIONS = 52;

    // 項目名の一覧
    const ITEM_TYPE_LIST = [
        self::ITEM_TYPE_NAME => 'お名前',
        self::ITEM_TYPE_KANA => 'ヨミ',
        self::ITEM_TYPE_EMAIL => 'メールアドレス',
        self::ITEM_TYPE_TEL => '電話番号',
        self::ITEM_TYPE_GENDER => '性別',
        self::ITEM_TYPE_ADDRESS => '住所',
//        self::ITEM_TYPE_TEXT => 'テキスト（短文）',
//        self::ITEM_TYPE_TEXTAREA => 'テキスト（長文）',
//        self::ITEM_TYPE_CHECKBOX => 'チェックボックス',
//        self::ITEM_TYPE_RADIO => 'ラジオボタン',
//        self::ITEM_TYPE_SELECT_BOX => 'セレクトボックス',
        self::ITEM_TYPE_TERMS => '利用規約',
        self::ITEM_TYPE_PRECAUTIONS => '注意事項',
    ];

    // 登録できる項目の上限値
    const ITEM_TYPE_UPPER_LIMIT = [
        self::ITEM_TYPE_NAME => 1,
        self::ITEM_TYPE_KANA => 1,
        self::ITEM_TYPE_EMAIL => 2,
        self::ITEM_TYPE_TEL => 1,
        self::ITEM_TYPE_GENDER => 1,
        self::ITEM_TYPE_ADDRESS => 1,
        self::ITEM_TYPE_TERMS => 1,
        self::ITEM_TYPE_PRECAUTIONS => 1,
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
    ];

}
