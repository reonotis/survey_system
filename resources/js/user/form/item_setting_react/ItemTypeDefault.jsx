import React, { useState, useEffect } from 'react';
import { getSelectedItem, saveItem } from './itemSettingData';

/**
 * 基本的な項目設定（itemTitle、fieldRequired、annotationText）
 * メール、電話、性別、住所などの項目タイプで使用
 *
 * - グローバルな itemSettingData ストアを直接参照して表示・保存します。
 * - 画面右側の詳細設定に依存しないため、どこからでも同じ状態を参照できます。
 */
function ItemTypeDefault({itemTypeList, itemType}) {
    const [itemTitle, setItemTitle] = useState('');
    const [fieldRequired, setFieldRequired] = useState(false);
    const [annotationText, setAnnotationText] = useState('');

    // データストアから最新のデータを取得
    useEffect(() => {
        const updateData = () => {
            const selectedItem = getSelectedItem();

            if (selectedItem && selectedItem.item_type === itemType) {
                setItemTitle(selectedItem.item_title || itemTypeList[itemType] || '');
                setFieldRequired(selectedItem.field_required || false);
                setAnnotationText(selectedItem.annotation_text || '');
            }
        };

        // マウント時に一度だけ実行
        updateData();
    }, [itemTypeList, itemType]);

    const handleFieldRequiredChange = (e) => {
        const checked = e.target.checked;
        setFieldRequired(checked);
        requestSave({ fieldRequired: checked });
    };

    const requestSave = (overrides = {}) => {
        const selectedItem = getSelectedItem();
        if (!selectedItem || selectedItem.item_type !== itemType) {
            console.warn(`item_type ${itemType} の項目が見つかりません`);
            return;
        }

        // 現在の details を取得
        const currentDetails =
            typeof selectedItem.details === 'string'
                ? JSON.parse(selectedItem.details)
                : selectedItem.details || {};

        // 保存処理を実行
        const payload = {
            item_title: overrides.item_title !== undefined ? overrides.item_title : itemTitle,
            field_required: overrides.fieldRequired !== undefined ? (overrides.fieldRequired ? 1 : 0) : (fieldRequired ? 1 : 0),
            annotation_text: overrides.annotation_text !== undefined ? overrides.annotation_text : annotationText,
            ...currentDetails, // 既存のdetailsを保持
        };

        saveItem(selectedItem.id, payload);
    };

    return (
        <div className="space-y-4">
            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">
                    {itemTypeList[itemType] || `タイプ: ${itemType}`}
                </p>
                <p className="text-xs text-gray-500 mt-1">
                    <input
                        type="text"
                        className="input-box w-full"
                        value={itemTitle}
                        onChange={(e) => setItemTitle(e.target.value)}
                        onBlur={() => requestSave({ item_title: itemTitle })}
                    />
                </p>
            </div>
            <div className="space-y-3">
                <div>
                    <label className="flex items-center">
                        <div className="checkbox-content">
                            <input
                                type="checkbox"
                                name="field_required"
                                className="checkbox"
                                onChange={handleFieldRequiredChange}
                                checked={fieldRequired}
                                value="1"
                            />
                        </div>
                        <span className="text-sm text-gray-700">必須項目</span>
                    </label>
                </div>
                <div>
                    <h4 className="text-sm font-semibold text-gray-700 mb-2">注釈文</h4>
                    <textarea
                        name="annotation_text"
                        className="input-box w-full h-20"
                        value={annotationText}
                        onChange={(e) => setAnnotationText(e.target.value)}
                        onBlur={() => requestSave({ annotation_text: annotationText })}
                    />
                </div>
            </div>
        </div>
    );
}

export default ItemTypeDefault;

