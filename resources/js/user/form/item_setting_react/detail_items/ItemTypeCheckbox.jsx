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
    // 形式: { 0: { name: "SNS", count: null }, ... }
    const [choicesText, setChoicesText] = useState(() => {
        if (!valueList || Object.keys(valueList).length === 0) {
            return '';
        }
        // インデックス順にソートしてnameを取得
        return Object.entries(valueList)
            .sort(([a], [b]) => parseInt(a) - parseInt(b))
            .map(([_, item]) => item.name)
            .join('\n');
    });

    // 選択肢の配列を取得（空行を除外）
    const choiceArray = useMemo(() => {
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
        // 形式: { 0: { name: "SNS", count: null }, 1: { name: "ホームページ", count: null }, ... }
        const newValueList = Object.fromEntries(
            choiceArray.map((name, index) => [
                index,
                {
                    name: name,
                    count: null
                }
            ])
        );

        updateItemLocalValue(selectedItem.id, 'value_list', newValueList);
        saveItemValue(selectedItem.id, 'value_list', newValueList);
    };

    // 選択可能最大数の変更
    const handleMaxCountChange = (choice, value) => {
        // choiceは選択肢名なので、該当するインデックスを見つける
        const newValueList = { ...valueList };
        Object.entries(newValueList).forEach(([index, item]) => {
            if (item.name === choice) {
                newValueList[index] = {
                    name: item.name,
                    count: value === '' ? null : parseInt(value, 10)
                };
            }
        });

        setValueList(newValueList);
        updateItemLocalValue(selectedItem.id, 'value_list', newValueList);
        // サーバー側のLaravelが自動的にJSONエンコードするため、オブジェクトをそのまま送信
        saveItemValue(selectedItem.id, 'value_list', newValueList);
    };

    useEffect(() => {

        // valueListを更新
        const newValueList = selectedItem.value_list ?? {};
        setValueList(newValueList);

        // choicesTextを更新
        if (!newValueList || Object.keys(newValueList).length === 0) {
            setChoicesText('');
        } else {
            // インデックス順にソートしてnameを取得
            const newText = Object.entries(newValueList)
                .sort(([a], [b]) => parseInt(a) - parseInt(b))
                .map(([_, item]) => item.name)
                .join('\n');
            setChoicesText(newText);
        }

        // detailsを更新
        const newDetails = selectedItem.details ?? {};
        setDetails(newDetails);
        setMaxCount(newDetails.max_count ?? '');
    }, [selectedItem.id]);

    // 選択肢名からcountを取得するヘルパー関数
    const getCountForChoice = (choiceName) => {
        if (!valueList || Object.keys(valueList).length === 0) {
            return null;
        }
        // 選択肢名で検索
        for (const [index, item] of Object.entries(valueList)) {
            if (item.name === choiceName) {
                return item.count ?? null;
            }
        }
        return null;
    };


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
                    {choiceArray.length > 0 && (
                        <>
                            <div className="flex items-end gap-2 mt-2">
                                <p className="text-sm font-medium text-gray-700">項目別上限値</p>
                                <p className="text-xs text-gray-500">※設定した上限値に到達した場合、その選択肢は選択できなくなります</p>
                            </div>
                            <div className="space-y-2">
                                {choiceArray.map((choice, index) => (
                                    <div key={index} className="flex items-center gap-2">
                                        <span className="text-sm text-gray-700 min-w-[120px]">{choice}</span>
                                        <input
                                            type="number"
                                            className="input-box w-20"
                                            value={getCountForChoice(choice) ?? ''}
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

