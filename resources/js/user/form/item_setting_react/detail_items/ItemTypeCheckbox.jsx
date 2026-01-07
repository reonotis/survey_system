import React, {useState, useEffect, useMemo} from 'react';
import CommonAnnotationText from "./component/CommonAnnotationText.jsx";
import CommonRequire from "./component/CommonRequire.jsx";
import CommonTitle from "./component/CommonTitle.jsx";

/**
 * チェックボックス設定
 */
function ItemTypeCheckbox({selectedItem, updateItemLocalValue, saveItemValue}) {

    const [valueList, setValueList] = useState(selectedItem.value_list ?? {});

    // 選択肢一覧のテキスト（改行区切り）
    const [choicesText, setChoicesText] = useState(() => {
        return Object.keys(valueList).join('\n');
    });

    // 選択肢の配列を取得（空行を除外）
    const choices = useMemo(() => {
        return choicesText
            .split('\n')
            .map(choice => choice.trim())
            .filter(choice => choice.length > 0);
    }, [choicesText]);

    // 選択肢一覧が変更されたとき（入力中はローカル状態のみ更新）
    const handleChoicesTextChange = (value) => {
        setChoicesText(value);
    };

    // 選択肢一覧の保存（フォーカスが外れたとき）
    const handleChoicesTextBlur = () => {
        const newChoices = choices;
        const toValueListObject = (arr) => {
            return Object.fromEntries(
                arr.map(key => [key, null])
            );
        };
        const newValueList = toValueListObject(newChoices);

        updateItemLocalValue(selectedItem.id, 'value_list', newValueList);
        saveItemValue(selectedItem.id, 'value_list', newValueList);
    };

    // 選択可能最大数の変更
    const handleMaxCountChange = (choice, value) => {
        const newValueList = {
            ...valueList,
            [choice]: value === '' ? null : parseInt(value, 10)
        };

        setValueList(newValueList);
        updateItemLocalValue(selectedItem.id, 'value_list', newValueList);
        // サーバー側のLaravelが自動的にJSONエンコードするため、オブジェクトをそのまま送信
        saveItemValue(selectedItem.id, 'value_list', newValueList);
    };

    // selectedItemが変更されたとき（別の項目を選択した場合など）に状態を更新
    useEffect(() => {

        // valueListを更新
        const newValueList = selectedItem.value_list ?? {};
        setValueList(newValueList);

        // choicesTextを更新
        const keys = Object.keys(newValueList);
        const newText = keys.length > 0 ? keys.join('\n') : '';
        setChoicesText(newText);

        // detailsを更新
        const newDetails = selectedItem.details ?? {};
        setDetails(newDetails);
        setMaxCount(newDetails.max_count ?? '');
    }, [selectedItem.id]);

    const currentValueList = valueList;


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
            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">選択肢</p>
                <div className="mt-2 pl-2">
                    <div className="flex items-end gap-2">
                        <p className="text-sm font-medium text-gray-700">選択肢一覧</p>
                        <p className="text-xs text-gray-500">※複数選択肢を改行して入力して下さい</p>
                    </div>
                    <textarea
                        name="choices_text"
                        className="input-box w-full h-24 mt-1"
                        value={choicesText}
                        onChange={e => handleChoicesTextChange(e.target.value)}
                        onBlur={handleChoicesTextBlur}
                        placeholder="選択肢を改行して入力"
                    />
                    {choices.length > 0 && (
                        <>
                            <div className="flex items-end gap-2 mt-2">
                                <p className="text-sm font-medium text-gray-700">項目別上限値</p>
                                <p className="text-xs text-gray-500">※設定した上限値に到達した場合、その選択肢は選択できなくなります</p>
                            </div>
                            <div className="space-y-2">
                                {choices.map((choice, index) => (
                                    <div key={index} className="flex items-center gap-2">
                                        <span className="text-sm text-gray-700 min-w-[120px]">{choice}</span>
                                        <input
                                            type="number"
                                            className="input-box w-20"
                                            value={currentValueList[choice] ?? ''}
                                            onChange={e => handleMaxCountChange(choice, e.target.value)}
                                            placeholder=""
                                            min="0"
                                        />
                                        <span className="text-sm text-gray-600">件</span>
                                    </div>
                                ))}
                            </div>
                        </>
                    )}
                </div>
            </div>
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

