import React, { useState, useEffect } from 'react';
import CommonAnnotationText from "./component/CommonAnnotationText.jsx";
import CommonRequire from "./component/CommonRequire.jsx";
import CommonTitle from "./component/CommonTitle.jsx";

/**
 * ヨミ項目の「セイ メイを分けるか」設定
 */
function ItemTypeKana({selectedItem, updateItemLocalValue, saveItemValue}) {

    // その他詳細
    const [details, setDetails] = useState(() => selectedItem.details);

    useEffect(() => {
        setDetails(selectedItem.details);
    }, [selectedItem.id]);

    // radio変更時は「イベント内で完結」
    const onChangeNameSeparateType = (value) => {
        const next = {
            ...details,
            kana_separate_type: Number(value),
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
                    {Object.entries(window.commonConst.KANA_SEPARATE_LIST || {}).map(
                        ([value, label]) => (
                            <div key={value} className="custom-radio">
                                <input
                                    type="radio"
                                    name="kana_separate_type"
                                    id={`kana_separate_type_${value}`}
                                    value={value}
                                    checked={details.kana_separate_type === Number(value)}
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
