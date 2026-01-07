import React, {useState} from 'react';
import CommonAnnotationText from "./component/CommonAnnotationText.jsx";
import CommonTitle from "./component/commonTitle.jsx";

/**
 * 利用規約設定
 */
function ItemTypeTerms({selectedItem, updateItemLocalValue, saveItemValue}) {

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
            <CommonTitle
                selectedItem={selectedItem}
                updateItemLocalValue={updateItemLocalValue}
                saveItemValue={saveItemValue}
            />
            <CommonAnnotationText
                selectedItem={selectedItem}
                updateItemLocalValue={updateItemLocalValue}
                saveItemValue={saveItemValue}
            />

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

