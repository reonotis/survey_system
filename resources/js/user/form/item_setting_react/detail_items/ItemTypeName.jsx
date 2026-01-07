import React, {useState, useEffect} from 'react';
import CommonAnnotationText from './component/CommonAnnotationText.jsx'
import CommonRequire from './component/CommonRequire.jsx'
import CommonTitle from './component/CommonTitle.jsx'

/**
 * お名前項目の「姓と名を別々にするか」設定
 */
function ItemTypeName({selectedItem, updateItemLocalValue, saveItemValue}) {

    // その他詳細
    const [details, setDetails] = useState(() => selectedItem.details);

    useEffect(() => {
        setDetails(selectedItem.details);
    }, [selectedItem.id]);

    // radio変更時は「イベント内で完結」
    const onChangeNameSeparateType = (value) => {
        const next = {
            ...details,
            name_separate_type: Number(value),
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

            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">
                    【姓と名を別々にするか】
                </p>
                <div className="flex gap-4 mt-1">
                    {Object.entries(window.commonConst.NAME_SEPARATE_LIST || {}).map(
                        ([value, label]) => (
                            <div key={value} className="custom-radio">
                                <input
                                    type="radio"
                                    name="name_separate_type"
                                    id={`name_separate_type_${value}`}
                                    value={value}
                                    checked={details.name_separate_type === Number(value)}
                                    onChange={() => onChangeNameSeparateType(value)}
                                />
                                <label htmlFor={`name_separate_type_${value}`} className="radio-label">
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

export default ItemTypeName;
