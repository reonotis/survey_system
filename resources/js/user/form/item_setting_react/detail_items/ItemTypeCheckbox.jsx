import React, {useState} from 'react';
import CommonAnnotationText from "./component/CommonAnnotationText.jsx";
import CommonChoices from "./component/CommonChoices.jsx";
import CommonRequire from "./component/CommonRequire.jsx";
import CommonTitle from "./component/CommonTitle.jsx";

/**
 * チェックボックス設定
 */
function ItemTypeCheckbox({selectedItem, updateItemLocalValue, saveItemValue}) {

    const [details, setDetails] = useState(() => selectedItem.details);
    const [maxCount, setMaxCount] = useState(details.max_count ?? '');
    const updateMaxCount = (value) => {
        const nextDetails = {
            ...details,
            max_count: Number(value),
        };
        setMaxCount(value)

        setDetails(nextDetails);
        updateItemLocalValue(selectedItem.id, 'details', nextDetails);
        saveItemValue(selectedItem.id, 'details', nextDetails);
    }

    return (
        <div className="space-y-4">
            <CommonTitle
                selectedItem={selectedItem}
                updateItemLocalValue={updateItemLocalValue}
                saveItemValue={saveItemValue}
            />
            <CommonRequire
                selectedItem={selectedItem}
                updateItemLocalValue={updateItemLocalValue}
                saveItemValue={saveItemValue}
            />
            <CommonAnnotationText
                selectedItem={selectedItem}
                updateItemLocalValue={updateItemLocalValue}
                saveItemValue={saveItemValue}
            />
            <CommonChoices
                selectedItem={selectedItem}
                updateItemLocalValue={updateItemLocalValue}
                saveItemValue={saveItemValue}
            />

            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">選択可能最大数</p>
                <p className="text-xs text-gray-500 mt-1">
                    <input
                        type="number"
                        className="input-box w-20"
                        value={maxCount}
                        onChange={e => updateMaxCount(e.target.value)}
                    />個
                </p>
            </div>
        </div>
    );
}

export default ItemTypeCheckbox;

