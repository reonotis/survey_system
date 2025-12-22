import React, { useState, useEffect } from 'react';

/**
 * ヨミ項目の「セイ メイを分けるか」設定
 */
function ItemTypeKana({selectedItem, updateItemLocalValue, saveItemValue}) {

    console.log(selectedItem)
    // タイトル
    const [title, setTitle] = useState(selectedItem.item_title ?? '');
    const updateTitle = (value) => {
        setTitle(value)
        updateItemLocalValue(selectedItem.id, 'item_title', value);
    }

    // 必須項目
    const updateFieldRequired = (value) => {
        const fieldRequiredValue =  value ? 1 : 0
        updateItemLocalValue(selectedItem.id, "field_required", fieldRequiredValue);
        saveItemValue(selectedItem.id, 'field_required', fieldRequiredValue)
    }

    // 注釈文
    const [annotationText, setAnnotationText] = useState(selectedItem.annotation_text ?? '');
    const updateAnnotationText = (value) => {
        setAnnotationText(value)
        updateItemLocalValue(selectedItem.id, 'annotation_text', value);
    }

    // その他詳細
    const parseDetails = (v) => {
        if (!v) return {};
        if (typeof v === 'string') {
            try { return JSON.parse(v); } catch { return {}; }
        }
        return v;
    };

    const [details, setDetails] = useState(() => parseDetails(selectedItem.details));

    useEffect(() => {
        setDetails(parseDetails(selectedItem.details));
    }, [selectedItem.id]);

    // radio変更時は「イベント内で完結」
    const onChangeNameSeparateType = (value) => {
        const next = {
            ...details,
            kana_separate_type: value,
        };

        setDetails(next);

        const json = JSON.stringify(next);
        updateItemLocalValue(selectedItem.id, 'details', json);
        saveItemValue(selectedItem.id, 'details', json);
    };

    return (
        <div className="space-y-4">
            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">
                    {window.itemTypeList[selectedItem.item_type]} のタイトル名
                </p>
                <p className="text-xs text-gray-500 mt-1">
                    <input
                        type="text"
                        className="input-box w-full"
                        value={title}
                        onChange={e => updateTitle(e.target.value)}
                        onBlur={e => {
                            saveItemValue(selectedItem.id, 'item_title', e.target.value)
                        }}
                    />
                </p>
            </div>
            <div className="border-b pb-3">
                <label className="flex items-center">
                    <div className="checkbox-content">
                        <input
                            type="checkbox"
                            name="field_required"
                            className="checkbox"
                            value="1"
                            checked={selectedItem.field_required}
                            onChange={e => updateFieldRequired(e.target.checked)}
                        />
                    </div>
                    <span className="text-sm text-gray-700">必須項目</span>
                </label>
            </div>
            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">
                    注釈文
                </p>
                <textarea
                    name="annotation_text"
                    className="input-box w-full h-20 mt-1"
                    value={annotationText}
                    onChange={e => updateAnnotationText(e.target.value)}
                    onBlur={e => {
                        saveItemValue(selectedItem.id, 'annotation_text', e.target.value)
                    }}
                />
            </div>
            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">
                    【姓と名を別々にするか】
                </p>
                <div className="flex gap-4 mt-1">
                    {Object.entries(window.commonConst.KANA_SEPARATE_LIST || {}).map(
                        ([value, label]) => (
                            <div key={value} className="custom-radio">
                                <input
                                    type="radio"
                                    name="kana_separate_type"
                                    id={`kana_separate_type_${value}`}
                                    value={value}
                                    checked={String(details.kana_separate_type) === String(value)}
                                    onChange={() => onChangeNameSeparateType(value)}
                                />
                                <label htmlFor={`kana_separate_type_${value}`} className="radio-label">
                                    <span className="outside">
                                        <span className="inside"></span>
                                    </span>
                                    {label}
                                </label>
                            </div>
                        ),
                    )}
                </div>
            </div>
        </div>
    );
}

export default ItemTypeKana;
