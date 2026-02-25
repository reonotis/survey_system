<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumTraits;

enum ItemType: int
{
    use EnumTraits;

    case NAME = 1;
    case KANA = 2;
    case EMAIL = 3;
    case TEL = 4;
    case GENDER = 5;
    case ADDRESS = 6;
//    case TEXT = 31;
//    case TEXTAREA = 32;
    case CHECKBOX = 34;
    case RADIO = 35;
    case SELECT_BOX = 36;
//    case SLIDER = 37;
    case TERMS = 51;
    case PRECAUTIONS = 52;

    /**
     * 日本語表示名
     */
    public function label(): string
    {
        return match ($this) {
            self::NAME => 'お名前',
            self::KANA => 'ヨミ',
            self::EMAIL => 'メールアドレス',
            self::TEL => '電話番号',
            self::GENDER => '性別',
            self::ADDRESS => '住所',
//            self::TEXT => 'テキスト（短文）',
//            self::TEXTAREA => 'テキスト（長文）',
            self::CHECKBOX => 'チェックボックス',
            self::RADIO => 'ラジオボタン',
            self::SELECT_BOX => 'セレクトボックス',
//            self::SLIDER => 'スライダー',
            self::TERMS => '利用規約',
            self::PRECAUTIONS => '注意事項',
        };
    }

}
