import React, {useState, useEffect} from 'react';

/**
 * 電話番号設定
 */
function ItemTypeTerms({selectedItem, updateItemLocalValue, saveItemValue}) {

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

    // 規約文言
    const [longText, setLongText] = useState(selectedItem.long_text ?? '');
    const updateLongText = (value) => {
        setLongText(value)
        updateItemLocalValue(selectedItem.id, 'long_text', value);
    }

    // その他詳細
    const [details, setDetails] = useState(() => selectedItem.details);

    // 「同意する」のラベル名
    const [labelName, setLabelName] = useState(details.label_name);
    const updateLabelName = (value) => {
        setLabelName(value)
    }
    const saveLabelName = (value) => {
        const next = {
            ...details,
            label_name: value,
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
                <p className="text-sm font-medium text-gray-800">注釈文</p>
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
                <p className="text-sm font-medium text-gray-800">規約文言</p>
                <textarea
                    name="long_text"
                    value={longText}
                    className="input-box w-full h-20 mt-1"
                    onChange={e => updateLongText(e.target.value)}
                    onBlur={e => {
                        saveItemValue(selectedItem.id, 'long_text', e.target.value)
                    }}
                />
            </div>
            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">「同意する」のラベル名</p>
                <p className="text-xs text-gray-500 mt-1">
                    <input
                        type="text"
                        className="input-box w-full"
                        value={labelName}
                        onChange={e => updateLabelName(e.target.value)}
                        onBlur={e => {
                            saveLabelName(e.target.value)
                        }}
                    />
                </p>
            </div>
        </div>
    );
}

export default ItemTypeTerms;

