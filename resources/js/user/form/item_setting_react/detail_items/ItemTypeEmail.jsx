import React, {useState, useEffect} from 'react';
import CommonAnnotationText from "./component/CommonAnnotationText.jsx";
import CommonRequire from "./component/CommonRequire.jsx";
import CommonTitle from './component/CommonTitle.jsx'

/**
 * メールアドレス設定
 */
function ItemTypeEmail({selectedItem, updateItemLocalValue, saveItemValue}) {

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

