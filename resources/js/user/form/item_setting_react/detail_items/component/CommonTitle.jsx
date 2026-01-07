import React, {useState, useEffect} from 'react';

/**
 * タイトル設定
 */
function CommonTitle({selectedItem, updateItemLocalValue, saveItemValue}) {
    // タイトル
    const [title, setTitle] = useState(selectedItem.item_title ?? '');
    const updateTitle = (value) => {
        setTitle(value)
        updateItemLocalValue(selectedItem.id, 'item_title', value);
    }

    useEffect(() => {
        setTitle(selectedItem.item_title ?? '');
    }, [selectedItem.id]);

    return (
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
    );
}

export default CommonTitle;

