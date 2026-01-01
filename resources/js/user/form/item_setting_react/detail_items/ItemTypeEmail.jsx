import React, {useState, useEffect} from 'react';

/**
 * メールアドレス設定
 */
function ItemTypeEmail({selectedItem, updateItemLocalValue, saveItemValue}) {
    // タイトル
    const [title, setTitle] = useState(selectedItem.item_title ?? '');
    const updateTitle = (value) => {
        setTitle(value)
        updateItemLocalValue(selectedItem.id, 'item_title', value);
    }

    useEffect(() => {
        setTitle(selectedItem.item_title ?? '');
    }, [selectedItem.id, selectedItem.item_title]);

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

    useEffect(() => {
        setAnnotationText(selectedItem.annotation_text ?? '');
    }, [selectedItem.id, selectedItem.annotation_text]);

    // その他詳細
    const [details, setDetails] = useState(() => selectedItem.details);

    useEffect(() => {
        setDetails(selectedItem.details);
    }, [selectedItem.id]);

    // radio変更時は「イベント内で完結」
    const onChangeNameConfirmType = (value) => {
        const next = {
            ...details,
            confirm_type: value,
        };

        setDetails(next);

        updateItemLocalValue(selectedItem.id, 'details', next);
        saveItemValue(selectedItem.id, 'details', next);
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
                    【確認用のメールアドレス項目を設ける】
                </p>

                <div className="flex gap-4 mt-1">
                    {Object.entries(window.commonConst.EMAIL_CONFIRM_LIST || {}).map(
                        ([value, label]) => (
                            <div key={value} className="custom-radio">
                                <input
                                    type="radio"
                                    name="confirm_type"
                                    id={`confirm_type_${value}`}
                                    value={value}
                                    checked={details.confirm_type === Number(value)}
                                    onChange={() => onChangeNameConfirmType(Number(value))}
                                />
                                <label htmlFor={`confirm_type_${value}`} className="radio-label">
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

export default ItemTypeEmail;

