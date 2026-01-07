import React, {useState, useEffect} from 'react';
import CommonAnnotationText from "./component/CommonAnnotationText.jsx";
import CommonRequire from "./component/CommonRequire.jsx";
import CommonTitle from "./component/CommonTitle.jsx";

/**
 * 電話番号設定
 */
function ItemTypeTel({selectedItem, updateItemLocalValue, saveItemValue}) {

    // 注釈文
    const [annotationText, setAnnotationText] = useState(selectedItem.annotation_text ?? '');
    const updateAnnotationText = (value) => {
        setAnnotationText(value)
        updateItemLocalValue(selectedItem.id, 'annotation_text', value);
    }

    // その他詳細
    const [details, setDetails] = useState(selectedItem.details);

    useEffect(() => {
        setDetails(selectedItem.details);
    }, [selectedItem.id]);

    // radio変更時は「イベント内で完結」
    const onChangeHyphenType = (value) => {
        const next = {
            ...details,
            hyphen_type: value,
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
                    【確認用のメールアドレス項目を設ける】
                </p>

                <div className="flex gap-4 mt-1">
                    {Object.entries(window.commonConst.TEL_HYPHEN_LIST || {}).map(
                        ([value, label]) => (
                            <div key={value} className="custom-radio">
                                <input
                                    type="radio"
                                    name="hyphen_type"
                                    id={`hyphen_type_${value}`}
                                    value={value}
                                    checked={Number(details.hyphen_type) === Number(value)}
                                    onChange={() => onChangeHyphenType(Number(value))}
                                />
                                <label htmlFor={`hyphen_type_${value}`} className="radio-label">
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

export default ItemTypeTel;

