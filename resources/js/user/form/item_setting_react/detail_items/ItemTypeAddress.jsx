import React, {useState, useEffect} from 'react';
import CommonAnnotationText from "./component/CommonAnnotationText.jsx";
import CommonRequire from "./component/CommonRequire.jsx";
import CommonTitle from "./component/CommonTitle.jsx";

/**
 * 電話番号設定
 */
function ItemTypeGender({selectedItem, updateItemLocalValue, saveItemValue}) {

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

    // radio変更時は「イベント内で完結」
    const onChangeDetails = (keyName, value) => {
        const next= {
            ...details,
            [keyName]: value,
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
                    【郵便番号を入力させるか】
                </p>
                <div className="flex gap-4 mt-1">
                    {Object.entries(window.commonConst.POST_CODE_USE_LIST || {}).map(
                        ([value, label]) => (
                            <div key={value} className="custom-radio">
                                <input
                                    type="radio"
                                    name="use_post_code_type"
                                    id={`use_post_code_type_${value}`}
                                    value={value}
                                    checked={details.use_post_code_type === Number(value)}
                                    onChange={() => onChangeDetails("use_post_code_type", Number(value))}
                                />
                                <label htmlFor={`use_post_code_type_${value}`} className="radio-label">
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
            <div className="pb-3">
                <p className="text-sm font-medium text-gray-800">
                    【住所の項目を分けるか】
                </p>
                <div className="flex gap-4 mt-1">
                    {Object.entries(window.commonConst.ADDRESS_SEPARATE_LIST || {}).map(
                        ([value, label]) => (
                            <div key={value} className="custom-radio">
                                <input
                                    type="radio"
                                    name="address_separate_type"
                                    id={`address_separate_type_${value}`}
                                    value={value}
                                    checked={details.address_separate_type === Number(value)}
                                    onChange={() => onChangeDetails("address_separate_type", Number(value))}
                                />
                                <label htmlFor={`address_separate_type_${value}`} className="radio-label">
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

export default ItemTypeGender;

