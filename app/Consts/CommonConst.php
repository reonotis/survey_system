<?php

namespace App\Consts;

class CommonConst
{
    public const GRAPH_TYPE_TOTAL = 1;
    public const GRAPH_TYPE_RATE = 2;
    public const GRAPH_TYPE_CIRCLE = 3;
    public const GRAPH_TYPE_VERTICAL = 4;
    public const GRAPH_TYPE_LIST = [
        self::GRAPH_TYPE_TOTAL => '総件数',
        self::GRAPH_TYPE_RATE => '回答率',
        self::GRAPH_TYPE_CIRCLE => '円グラフ',
        self::GRAPH_TYPE_VERTICAL => '棒グラフ',
    ];

    public const WIDGET_TYPE_1 = 1;
    public const WIDGET_TYPE_2 = 2;
    public const WIDGET_TYPE_3 = 3;
    public const WIDGET_TYPE_4 = 4;
    public const WIDGET_TYPE_5 = 5;
    public const WIDGET_TYPE_6 = 6;
    public const WIDGET_TYPE_7 = 7;
    public const WIDGET_TYPE_8 = 8;
    public const WIDGET_TYPE_9 = 9;
    public const WIDGET_TYPE_10 = 10;
    public const WIDGET_TYPE_LIST = [
        self::WIDGET_TYPE_1 => [
            'label' => '1分割',
            'image_file' => 'split_1.png',
        ],
        self::WIDGET_TYPE_2 => [
            'label' => '2分割',
            'image_file' => 'split_2.png',
        ],
        self::WIDGET_TYPE_3 => [
            'label' => '2分割(左メイン)',
            'image_file' => 'split_2_left.png',
        ],
        self::WIDGET_TYPE_4 => [
            'label' => '2分割(右メイン)',
            'image_file' => 'split_2_right.png',
        ],
        self::WIDGET_TYPE_5 => [
            'label' => '3分割(均等)',
            'image_file' => 'split_3.png',
        ],
        self::WIDGET_TYPE_6 => [
            'label' => '3分割(真ん中がメイン)',
            'image_file' => 'split_3_center.png',
        ],
        self::WIDGET_TYPE_7 => [
            'label' => '3分割(左メイン)',
        'image_file' => 'split_3_left.png',
        ],
        self::WIDGET_TYPE_8 => [
            'label' => '3分割(右メイン)',
            'image_file' => 'split_3_right.png',
        ],
        self::WIDGET_TYPE_9 => [
            'label' => '4分割',
            'image_file' => 'split_4.png',
        ],
        self::WIDGET_TYPE_10 => [
            'label' => '5分割',
            'image_file' => 'split_5.png',
        ],
    ];
    public const WIDGET_TYPE_COLUMN = [
        self::WIDGET_TYPE_1 => [60],
        self::WIDGET_TYPE_2 => [30, 30],
        self::WIDGET_TYPE_3 => [45, 15],
        self::WIDGET_TYPE_4 => [15, 45],
        self::WIDGET_TYPE_5 => [20, 20, 20],
        self::WIDGET_TYPE_6 => [15, 30, 15],
        self::WIDGET_TYPE_7 => [30, 15, 15],
        self::WIDGET_TYPE_8 => [15, 15, 30],
        self::WIDGET_TYPE_9 => [15, 15, 15, 15],
        self::WIDGET_TYPE_10 => [12, 12, 12, 12, 12],
    ];

    // 利用可否
    public const USE_TYPE_DISABLED = 0;
    public const USE_TYPE_ENABLED = 1;
    public const USE_TYPE_LIST = [
        self::USE_TYPE_DISABLED => '利用しない',
        self::USE_TYPE_ENABLED => '利用する',
    ];

    // 名前
    public const NAME_SEPARATE = 1;
    public const NAME_NON_SEPARATE = 2;
    public const NAME_SEPARATE_LIST = [
        self::NAME_SEPARATE => '姓名を分ける',
        self::NAME_NON_SEPARATE => '姓名を分けない',
    ];

    // ヨミ
    public const KANA_SEPARATE = 1;
    public const KANA_NON_SEPARATE = 2;
    public const KANA_SEPARATE_LIST = [
        self::KANA_SEPARATE => 'セイ メイを分ける',
        self::KANA_NON_SEPARATE => 'セイ メイを分けない',
    ];

    // メールアドレス
    public const EMAIL_CONFIRM_ENABLED = 1;
    public const EMAIL_CONFIRM_DISABLED = 2;
    public const EMAIL_CONFIRM_LIST = [
        self::EMAIL_CONFIRM_ENABLED => '確認用の項目を設ける',
        self::EMAIL_CONFIRM_DISABLED => '確認用の項目を設けない',
    ];

    // 電話番号
    public const TEL_HYPHEN_USE = 1;
    public const TEL_HYPHEN_UN_USE = 2;
    public const TEL_HYPHEN_LIST = [
        self::TEL_HYPHEN_USE => 'ハイフンを入力させる',
        self::TEL_HYPHEN_UN_USE => 'ハイフンを入力させない',
    ];

    // 住所
    public const POST_CODE_DISABLED = 0;
    public const POST_CODE_ENABLED = 1;
    public const POST_CODE_USE_LIST = [
        self::POST_CODE_DISABLED => '郵便番号を入力させない',
        self::POST_CODE_ENABLED => '郵便番号を入力させる',
    ];
    public const ADDRESS_SEPARATE = 1;
    public const ADDRESS_NON_SEPARATE = 2;
    public const ADDRESS_SEPARATE_LIST = [
        self::ADDRESS_SEPARATE => '住所の項目を分ける',
        self::ADDRESS_NON_SEPARATE => '住所の項目を分けない',
    ];

    // 性別のリスト
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;
    public const GENDER_OTHER = 3;
    public const GENDER_UNKNOWN = 4;
    public const GENDER_LIST = [
        self::GENDER_MALE => '男性',
        self::GENDER_FEMALE => '女性',
        self::GENDER_OTHER => 'その他',
        self::GENDER_UNKNOWN => '未回答',
    ];


    // デザイン
    public const DESIGN_TYPE_A = 1;
    public const DESIGN_TYPE_B = 2;
    public const DESIGN_TYPE_C = 3;
    public const DESIGN_TYPE_LIST = [
        self::DESIGN_TYPE_A => 'タイプA',
        self::DESIGN_TYPE_B => 'タイプB',
        self::DESIGN_TYPE_C => 'タイプC',
    ];

    // 分析画面、期間内応募選択肢
    public const ANALYTICS_TYPE_DAY = 1;
    public const ANALYTICS_TYPE_WEEK = 2;
    public const ANALYTICS_TYPE_MONTH = 3;
    public const ANALYTICS_TYPE_LIST = [
        self::ANALYTICS_TYPE_DAY => '日別',
        self::ANALYTICS_TYPE_WEEK => '週別',
        self::ANALYTICS_TYPE_MONTH => '月別',
    ];
}
