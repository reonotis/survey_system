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

    // 選択肢
    const [selectedGenders, setSelectedGenders] = useState(details.gender_list);

    const onChangeGender = (e) => {
        const value = e.target.value;
        const checked = e.target.checked;

        setSelectedGenders(prev =>
            checked
                ? [...prev, value]
                : prev.filter(v => v !== value)
        );
    };

    useEffect(() => {
        const nextDetails = {
            ...details,
            gender_list: selectedGenders,
        };

        setDetails(nextDetails);

    }, [selectedGenders]);

    useEffect(() => {
        const json = JSON.stringify(details);

        updateItemLocalValue(selectedItem.id, 'details', json);
        saveItemValue(selectedItem.id, 'details', json);

    }, [details]);

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
                    【利用する選択肢】
                </p>
                <div className="flex gap-4 mt-1">
                    {Object.entries(window.commonConst.GENDER_LIST || {}).map(
                        ([value, label]) => (
                            <div key={value} className="checkbox-content">
                                <input type="checkbox"
                                       name="gender[]"
                                       id={`gender_${value}`}
                                       className="checkbox"
                                       value={value}
                                       checked={selectedGenders.includes(value)}
                                       onChange={onChangeGender}
                                />
                                <label className="checkbox-item" htmlFor={`gender_${value}`}>{label}</label>
                            </div>
                        ),
                    )}
                </div>
            </div>
        </div>
    );
}

export default ItemTypeGender;

