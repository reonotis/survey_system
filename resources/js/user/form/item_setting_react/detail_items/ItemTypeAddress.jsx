import React, {useState, useEffect} from 'react';

/**
 * 電話番号設定
 */
function ItemTypeGender({selectedItem, updateItemLocalValue, saveItemValue}) {
    // タイトル
    const [title, setTitle] = useState(selectedItem.item_title ?? '');
    const updateTitle = (value) => {
        setTitle(value)
        updateItemLocalValue(selectedItem.id, 'item_title', value);
    }

    // 必須項目
    const updateFieldRequired = (value) => {
        const fieldRequiredValue = value ? 1 : 0
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
            try {
                return JSON.parse(v);
            } catch {
                return {};
            }
        }
        return v;
    };

    // その他詳細
    const [details, setDetails] = useState(() => parseDetails(selectedItem.details));

    // radio変更時は「イベント内で完結」
    const onChangeDetails = (keyName, value) => {
        const next= {
            ...details,
            [keyName]: value,
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
                <label className="flex items-center w-fit cursor-pointer">
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
                    <span className="text-sm text-gray-700 p-1">必須項目</span>
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
                    【郵便番号を入力させるか】
                </p>
                <div className="flex gap-4 mt-1">
                    {Object.entries(window.commonConst.POST_CODE_USE_LIST || {}).map(
                        ([value, label]) => (
                            <div key={value} className="custom-radio">
                                <input
                                    type="radio"
                                    name="use_post_code_type"
                                    id={`use_post_code_type_${value}`}
                                    value={value}
                                    checked={String(details.use_post_code_type) === String(value)}
                                    onChange={() => onChangeDetails("use_post_code_type", value)}
                                />
                                <label htmlFor={`use_post_code_type_${value}`} className="radio-label">
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
            <div className="pb-3">
                <p className="text-sm font-medium text-gray-800">
                    【住所の項目を分けるか】
                </p>
                <div className="flex gap-4 mt-1">
                    {Object.entries(window.commonConst.ADDRESS_SEPARATE_LIST || {}).map(
                        ([value, label]) => (
                            <div key={value} className="custom-radio">
                                <input
                                    type="radio"
                                    name="address_separate_type"
                                    id={`address_separate_type_${value}`}
                                    value={value}
                                    checked={String(details.address_separate_type) === String(value)}
                                    onChange={() => onChangeDetails("address_separate_type", value)}
                                />
                                <label htmlFor={`address_separate_type_${value}`} className="radio-label">
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

export default ItemTypeGender;

