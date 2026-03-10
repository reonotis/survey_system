import React, { useState } from 'react';

// 総件数ウィジェット用の表示形式コード
// PHP 側の CommonConst::GRAPH_TYPE_TOTAL (1) に合わせる
const GRAPH_TYPE_TOTAL = '1';

/**
 * 応募分析ダッシュボードの「ウィジェット追加」モーダル内フォーム
 *
 * - 表示形式（総件数 / 回答率 / 円グラフ / 棒グラフ ...）をラジオボタンで選択
 * - 表示形式ごとに、選択可能なフォーム項目（対象フォーム項目）を絞り込む
 * - 送信先は画面遷移を伴う通常の POST（React では送信イベントをフックしない）
 *
 * props:
 * - formItems: ウィジェット追加時に選択可能なフォーム項目（PHP 側で整形済み）
 * - rowId   : どの行にウィジェットを配置するか
 * - columnId: 行の中のどのカラムに配置するか
 * - urlWidgetAdd: ウィジェット登録用の POST URL
 */
export function WidgetAdd({formItems, rowId, columnId, urlWidgetAdd}) {

    // Blade 側で埋め込まれた meta タグから CSRF トークンを取得
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    // 表示形式の候補一覧（例: { "1": "総件数", "2": "回答率", ... }）
    const graphTypes = window.graphTypes ?? [];

    // 表示形式ごとに選択可能な item_type 一覧
    // 例: { "2": [1, 2, 3, ...], "3": [5, 34, 35, 36], ... }
    const graphTypeItemTypeMap = window.graphTypeItemTypeMap ?? {};

    // 現在選択中の表示形式（ラジオボタンの value）
    const [selectedGraphType, setSelectedGraphType] = useState('');

    // 「対象フォーム項目」のセレクトボックスを表示するかどうか
    // - 空文字: 何も選ばれていないので非表示
    // - マッピング配列が空: 項目に依存しない表示形式（総件数など）なので非表示
    // - それ以外: 対象フォーム項目を選ばせるため表示
    const showFormItemSelect = selectedGraphType !== ''
        && (graphTypeItemTypeMap[selectedGraphType]?.length ?? 0) > 0;

    // 選択された表示形式で選択可能なフォーム項目のみを抽出
    // - 回答率 → 名前 / メール など
    // - 円グラフ / 棒グラフ → 性別 / チェックボックス / ラジオ / セレクトボックス など
    const filteredFormItems = showFormItemSelect
        ? formItems.filter((item) => {
            const allowedTypes = graphTypeItemTypeMap[selectedGraphType] || [];
            return allowedTypes.includes(item.item_type);
        })
        : [];

    return (
        <>
            <form method="POST" action={urlWidgetAdd}>
                <input type="hidden" name="_token" value={csrfToken} readOnly />
                <input type="hidden" name="row_id" value={rowId} readOnly />
                <input type="hidden" name="column_id" value={columnId} readOnly />

                {/* 表示形式（グラフ種別）の選択エリア */}
                <div className="mt-4">
                    <fieldset>
                        <legend className="block text-sm font-medium text-gray-700 mb-1">表示形式</legend>

                        <div className="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                            {Object.entries(graphTypes).map(([value, label], index) => {
                                const id = `graph_type_${value}`;
                                return (
                                    <div className="custom-radio" key={value}>
                                        <input
                                            id={id}
                                            type="radio"
                                            name="graph_type"
                                            value={value}
                                            // どの表示形式が選ばれているかの制御
                                            checked={selectedGraphType === value}
                                            onChange={() => setSelectedGraphType(value)}
                                            className="border-gray-300"
                                        />
                                        <label htmlFor={id} className="radio-label">
                                            <span className="outside"><span className="inside"></span></span>{label}
                                        </label>
                                    </div>
                                );
                            })}
                        </div>
                    </fieldset>
                </div>

                {/* 対象フォーム項目のセレクトボックス
                    - showFormItemSelect が true のときだけ表示
                    - filteredFormItems によって、表示形式と相性の良い項目だけを選択肢として出す */}
                {showFormItemSelect && (
                    <div className="">
                        <label className="block text-sm font-medium text-gray-700 mb-1">対象フォーム項目</label>
                        <select
                            name="form_item_id"
                            className="w-full"
                            required
                        >
                            <option value="" disabled>項目を選択してください</option>
                            {filteredFormItems.map((item) => (
                                <option key={item.id} value={item.id}>{item.item_title}</option>
                            ))}
                        </select>
                    </div>
                )}
                {/* 項目選択が不要な表示形式（総件数など）の場合は hidden で空値を送る */}
                {!showFormItemSelect && <input type="hidden" name="form_item_id" value="" />}

                <div className="mt-6 flex justify-end gap-2">
                    <button
                        type="submit"
                        className="btn"
                    >登録する
                    </button>
                </div>
            </form>
        </>
    );
}

