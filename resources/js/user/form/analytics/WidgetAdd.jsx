import React, { useState } from 'react';

// 総件数は CommonConst::GRAPH_TYPE_TOTAL = 1
const GRAPH_TYPE_TOTAL = '1';

// 画面遷移する通常の POST フォーム送信用コンポーネント
// actionUrl は実際の URL（例: route('user_form_analytics_add_widget') のパス）を渡してください
export function WidgetAdd({formItems, rowId, columnId, urlWidgetAdd}) {

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
    const graphTypes = window.graphTypes ?? [];
    const [selectedGraphType, setSelectedGraphType] = useState('');
    const showFormItemSelect = selectedGraphType !== '' && selectedGraphType !== GRAPH_TYPE_TOTAL;

    return (
        <>
            <form method="POST" action={urlWidgetAdd}>
                <input type="hidden" name="_token" value={csrfToken} readOnly />
                <input type="hidden" name="row_id" value={rowId} readOnly />
                <input type="hidden" name="column_id" value={columnId} readOnly />

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

                {showFormItemSelect && (
                    <div className="">
                        <label className="block text-sm font-medium text-gray-700 mb-1">対象フォーム項目</label>
                        <select
                            name="form_item_id"
                            className="w-full"
                            required
                        >
                            <option value="" disabled>項目を選択してください</option>
                            {formItems.map((item) => (
                                <option key={item.id} value={item.id}>{item.item_title}</option>
                            ))}
                        </select>
                    </div>
                )}
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

