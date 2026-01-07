import React, {useState, useEffect} from 'react';
import CommonAnnotationText from "./component/CommonAnnotationText.jsx";
import CommonRequire from "./component/CommonRequire.jsx";
import CommonTitle from "./component/CommonTitle.jsx";

/**
 * 電話番号設定
 */
function ItemTypeGender({selectedItem, updateItemLocalValue, saveItemValue}) {

    // その他詳細
    const [details, setDetails] = useState(selectedItem.details);
    const [selectedGenders, setSelectedGenders] = useState(selectedItem.details.gender_list);

    const onChangeGender = (e) => {
        const value = Number(e.target.value);
        const checked = e.target.checked;

        setSelectedGenders(prev =>
            checked
                ? Array.from(new Set([...prev, value])).sort((a, b) => a - b)
                : prev.filter(v => v !== value)
        );
    };

    useEffect(() => {
        const nextDetails = {
            ...details,
            gender_list: selectedGenders,
        };

        setDetails(nextDetails);
        updateItemLocalValue(selectedItem.id, 'details', nextDetails);
        saveItemValue(selectedItem.id, 'details', nextDetails);
    }, [selectedGenders]);


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
                    【利用する選択肢】
                </p>
                <div className="flex gap-4 mt-1">
                    {Object.entries(window.commonConst.GENDER_LIST || {}).map(
                        ([value, label]) => {
                            const intValue = Number(value);

                            return (
                                <label key={value} className="checkbox-content cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="gender"
                                        className="checkbox"
                                        value={intValue}
                                        checked={selectedGenders.includes(intValue)}
                                        onChange={onChangeGender}
                                    />
                                    <span className="checkbox-item">{label}</span>
                                </label>
                            );
                        }
                    )}
                </div>
            </div>
        </div>
    );
}

export default ItemTypeGender;

