import React, {useState} from 'react';
import CommonAnnotationText from "./component/CommonAnnotationText.jsx";
import CommonTitle from "./component/commonTitle.jsx";

/**
 * 注意事項設定
 */
function ItemTypePrecautions({selectedItem, updateItemLocalValue, saveItemValue}) {

    // 注意事項文言
    const [longText, setLongText] = useState(selectedItem.long_text ?? '');
    const updateLongText = (value) => {
        setLongText(value)
        updateItemLocalValue(selectedItem.id, 'long_text', value);
    }

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
                <p className="text-sm font-medium text-gray-800">注意事項文言</p>
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
        </div>
    );
}

export default ItemTypePrecautions;

