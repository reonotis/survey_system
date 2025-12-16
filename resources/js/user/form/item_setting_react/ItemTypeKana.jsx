import React, { useState, useEffect } from 'react';
import { getItemById, getCommonConsts, getSelectedItem, getAllData, saveItem } from './itemSettingData';

/**
 * ヨミ項目の「セイ メイを分けるか」設定
 *
 * - グローバルな itemSettingData ストアを直接参照して表示・保存します。
 * - 画面右側の詳細設定に依存しないため、どこからでも同じ状態を参照できます。
 */
function ItemTypeKana({itemTypeList, selectedItem: propSelectedItem}) {
    const [nameTypeKana, setNameTypeKana] = useState(1);
    const [commonConsts, setCommonConsts] = useState({});
    const [itemTitle, setItemTitle] = useState('');
    const [fieldRequired, setFieldRequired] = useState(false);
    const [annotationText, setAnnotationText] = useState('');

    // データストアから最新のデータを取得
    useEffect(() => {
        const updateData = () => {
            // 選択されている項目がヨミ項目（item_type: 2）の場合、その項目の details を使用
            // そうでない場合は、選択中の項目のIDを使用して取得
            const selectedItem = propSelectedItem || getSelectedItem();
            let kanaItem = null;

            if (selectedItem && selectedItem.item_type === 2) {
                kanaItem = selectedItem;
            } else if (selectedItem && selectedItem.id) {
                kanaItem = getItemById(selectedItem.id);
            }

            if (kanaItem) {
                if (kanaItem.details) {
                    const details =
                        typeof kanaItem.details === 'string'
                            ? JSON.parse(kanaItem.details)
                            : kanaItem.details;
                    setNameTypeKana(details.name_type_kana || 1);
                }
                setItemTitle(kanaItem.item_title || itemTypeList[2] || '');
                setFieldRequired(kanaItem.field_required || false);
                setAnnotationText(kanaItem.annotation_text || '');
            }
            setCommonConsts(getCommonConsts());

            // デバッグ用：ストア全体をコンソールに一度だけ出力
            const allData = getAllData();
        };

        // マウント時に一度だけ実行（ポーリングしない）
        updateData();
    }, [itemTypeList, propSelectedItem]);

    const handleChange = (value) => {
        const newNameTypeKana = parseInt(value);
        setNameTypeKana(newNameTypeKana);
        requestSave({ name_type_kana: newNameTypeKana });
    };

    const handleFieldRequiredChange = (e) => {
        const checked = e.target.checked;
        setFieldRequired(checked);
        requestSave({ fieldRequired: checked });
    };

    const requestSave = (overrides = {}) => {
        // 選択中の項目を取得して更新
        const selectedItem = propSelectedItem || getSelectedItem();
        if (!selectedItem || !selectedItem.id) {
            console.warn('ヨミ項目が見つかりません');
            return;
        }
        const kanaItem = getItemById(selectedItem.id);
        if (!kanaItem) {
            console.warn('ヨミ項目が見つかりません');
            return;
        }

        // 現在の details を更新
        const currentDetails =
            typeof kanaItem.details === 'string'
                ? JSON.parse(kanaItem.details)
                : kanaItem.details || {};

        const updatedDetails = overrides.name_type_kana !== undefined
            ? { ...currentDetails, name_type_kana: overrides.name_type_kana }
            : currentDetails;

        // 保存処理を実行（ItemDetailPanel の buildPayload ロジックを簡易的に再現）
        const payload = {
            item_title: overrides.item_title !== undefined ? overrides.item_title : itemTitle,
            field_required: overrides.fieldRequired !== undefined ? (overrides.fieldRequired ? 1 : 0) : (fieldRequired ? 1 : 0),
            annotation_text: overrides.annotation_text !== undefined ? overrides.annotation_text : annotationText,
            name_type_kana: updatedDetails.name_type_kana || nameTypeKana,
        };

        saveItem(kanaItem.id, payload);
    };

    return (
        <div className="space-y-4">
            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">
                    {itemTypeList[2] || `タイプ: ${2}`}
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
            <div className="border-t pt-4">
                <div className="space-y-4">
                    <div>
                        <h4 className="text-sm font-semibold text-gray-700 mb-2">
                            【セイ メイを分けるか】
                        </h4>
                        <div className="flex gap-4">
                            {Object.entries(commonConsts.KANA_SEPARATE_LIST || {}).map(
                                ([value, label]) => (
                                    <div key={value} className="custom-radio">
                                        <input
                                            type="radio"
                                            name="name_type_kana"
                                            id={`name_type_kana_${value}`}
                                            value={value}
                                            checked={parseInt(nameTypeKana) === parseInt(value)}
                                            onChange={(e) => handleChange(e.target.value)}
                                        />
                                        <label htmlFor={`name_type_kana_${value}`} className="radio-label">
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
            </div>
        </div>
    );
}

export default ItemTypeKana;
