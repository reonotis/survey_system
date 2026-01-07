import React, {useState, useEffect} from 'react';

/**
 * 注釈文の設定
 */
function CommonAnnotationText({selectedItem, updateItemLocalValue, saveItemValue}) {
    // 注釈文
    const [annotationText, setAnnotationText] = useState(selectedItem.annotation_text ?? '');
    const updateAnnotationText = (value) => {
        setAnnotationText(value)
        updateItemLocalValue(selectedItem.id, 'annotation_text', value);
    }

    useEffect(() => {
        setAnnotationText(selectedItem.annotation_text ?? '');
    }, [selectedItem.id]);


    return (
        <div className="border-b pb-3">
            <p className="text-sm font-medium text-gray-800">
                注釈文
            </p>
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
    );
}

export default CommonAnnotationText;

