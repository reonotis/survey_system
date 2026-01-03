import React, {useState} from 'react';

/**
 * 注意事項設定
 */
function ItemTypePrecautions({selectedItem, updateItemLocalValue, saveItemValue}) {

    // タイトル
    const [title, setTitle] = useState(selectedItem.item_title ?? '');
    const updateTitle = (value) => {
        setTitle(value)
        updateItemLocalValue(selectedItem.id, 'item_title', value);
    }

    // 注釈文
    const [annotationText, setAnnotationText] = useState(selectedItem.annotation_text ?? '');
    const updateAnnotationText = (value) => {
        setAnnotationText(value)
        updateItemLocalValue(selectedItem.id, 'annotation_text', value);
    }

    // 注意事項文言
    const [longText, setLongText] = useState(selectedItem.long_text ?? '');
    const updateLongText = (value) => {
        setLongText(value)
        updateItemLocalValue(selectedItem.id, 'long_text', value);
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
                <p className="text-sm font-medium text-gray-800">注意事項文言</p>
                <textarea
                    name="long_text"
                    value={longText}
                    className="input-box w-full h-20 mt-1"
                    onChange={e => updateLongText(e.target.value)}
                    onBlur={e => {
                        saveItemValue(selectedItem.id, 'long_text', e.target.value)
                    }}
                />
            </div>
        </div>
    );
}

export default ItemTypePrecautions;

