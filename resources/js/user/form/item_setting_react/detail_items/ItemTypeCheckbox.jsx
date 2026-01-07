import React, {useState, useEffect, useMemo} from 'react';

/**
 * チェックボックス設定
 */
function ItemTypeCheckbox({selectedItem, updateItemLocalValue, saveItemValue}) {
    // タイトル
    const [title, setTitle] = useState(selectedItem.item_title ?? '');
    const updateTitle = (value) => {
        setTitle(value)
        updateItemLocalValue(selectedItem.id, 'item_title', value);
    }

    // 必須項目
    const updateFieldRequired = (value) => {
        const fieldRequiredValue = value ? 1 : 0
        updateItemLocalValue(selectedItem.id, "field_required", fieldRequiredValue);
        saveItemValue(selectedItem.id, 'field_required', fieldRequiredValue)
    }

    // 注釈文
    const [annotationText, setAnnotationText] = useState(selectedItem.annotation_text ?? '');
    const updateAnnotationText = (value) => {
        setAnnotationText(value)
        updateItemLocalValue(selectedItem.id, 'annotation_text', value);
    }

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

    // selectedItemが変更されたとき（別の項目を選択した場合など）のみchoicesTextを更新
    useEffect(() => {
        const keys = Object.keys(valueList);
        const newText = keys.length > 0 ? keys.join('\n') : '';

        // 現在のテキストと異なる場合のみ更新（入力中の上書きを防ぐ）
        if (newText !== choicesText) {
            setChoicesText(newText);
        }
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
            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">
                    {window.itemTypeList[selectedItem.item_type]} のタイトル名
                </p>
                <p className="text-xs text-gray-500 mt-1">
                    <input
                        type="text"
                        className="input-box w-full"
                        value={title}
                        onChange={e => updateTitle(e.target.value)}
                        onBlur={e => {
                            saveItemValue(selectedItem.id, 'item_title', e.target.value)
                        }}
                    />
                </p>
            </div>
            <div className="border-b pb-3">
                <label className="flex items-center w-fit cursor-pointer">
                    <div className="checkbox-content">
                        <input
                            type="checkbox"
                            name="field_required"
                            className="checkbox"
                            value="1"
                            checked={selectedItem.field_required}
                            onChange={e => updateFieldRequired(e.target.checked)}
                        />
                    </div>
                    <span className="text-sm text-gray-700 p-1">必須項目</span>
                </label>
            </div>
            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">注釈文</p>
                <textarea
                    name="annotation_text"
                    className="input-box w-full h-20 mt-1"
                    value={annotationText}
                    onChange={e => updateAnnotationText(e.target.value)}
                    onBlur={e => {
                        saveItemValue(selectedItem.id, 'annotation_text', e.target.value)
                    }}
                />
            </div>
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

