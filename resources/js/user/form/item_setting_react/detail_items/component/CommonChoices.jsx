import React, {useState, useEffect, useMemo} from 'react';

/**
 */
function CommonChoices({selectedItem, updateItemLocalValue, saveItemValue}) {

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
        // 既存のキー（0,1,2,...) をそのまま使い、同じキーのnameが変わっていなければcountを引き継ぐ
        const newValueList = Object.fromEntries(
            choiceArray.map((name, index) => {
                const prev = valueList[index];
                const count = prev && prev.name === name ? prev.count : null;
                return [
                    index,
                    {
                        name,
                        count,
                    },
                ];
            })
        );

        setValueList(newValueList);
        updateItemLocalValue(selectedItem.id, 'value_list', newValueList);
        saveItemValue(selectedItem.id, 'value_list', newValueList);
    };

    // 選択可能最大数の変更（キー番号で更新）
    const handleMaxCountChange = (key, value) => {
        const newValueList = {
            ...valueList,
            [key]: {
                ...valueList[key],
                count: value === '' ? null : parseInt(value, 10),
            },
        };

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

            ('');
        } else {
            // インデックス順にソートしてnameを取得
            const newText = Object.entries(newValueList)
                .sort(([a], [b]) => parseInt(a) - parseInt(b))
                .map(([_, item]) => item.name)
                .join('\n');
            setChoicesText(newText);
        }

    }, [selectedItem.id]);



    return (
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
                {/* 項目別上限値（valueListのキー順で表示） */}
                {Object.keys(valueList).length > 0 && (
                    <>
                        <div className="flex items-end gap-2 mt-2">
                            <p className="text-sm font-medium text-gray-700">項目別上限値</p>
                            <p className="text-xs text-gray-500">※設定した上限値に到達した場合、その選択肢は選択できなくなります</p>
                        </div>
                        <div className="space-y-2">
                            {Object.entries(valueList)
                                .sort(([a], [b]) => parseInt(a) - parseInt(b))
                                .map(([key, item]) => (
                                    <div key={key} className="flex items-center gap-2">
                                            <span className="text-sm text-gray-700 min-w-[120px]">
                                                {item.name}
                                            </span>
                                        <input
                                            type="number"
                                            className="input-box w-20"
                                            value={item.count ?? ''}
                                            onChange={e => handleMaxCountChange(key, e.target.value)}
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
    );
}

export default CommonChoices;

