import React, { useState, useEffect } from 'react';
import { getItemById, getCommonConsts, getSelectedItem, getAllData, saveItem } from './itemSettingData';

/**
 * お名前項目の「姓と名を別々にするか」設定
 *
 * - グローバルな itemSettingData ストアを直接参照して表示・保存します。
 * - 画面右側の詳細設定に依存しないため、どこからでも同じ状態を参照できます。
 */
function ItemTypeName({itemTypeList, selectedItem: propSelectedItem}) {
    const [nameType, setNameType] = useState(1);
    const [commonConsts, setCommonConsts] = useState({});
    const [itemTitle, setItemTitle] = useState('');
    const [fieldRequired, setFieldRequired] = useState(false);
    const [annotationText, setAnnotationText] = useState('');

    // データストアから最新のデータを取得
    useEffect(() => {
        const updateData = () => {
            // 選択されている項目がお名前項目（item_type: 1）の場合、その項目の details を使用
            // そうでない場合は、選択中の項目のIDを使用して取得
            const selectedItem = propSelectedItem || getSelectedItem();
            let nameItem = null;

            if (selectedItem && selectedItem.item_type === 1) {
                nameItem = selectedItem;
            } else if (selectedItem && selectedItem.id) {
                nameItem = getItemById(selectedItem.id);
            }

            if (nameItem) {
                if (nameItem.details) {
                    const details =
                        typeof nameItem.details === 'string'
                            ? JSON.parse(nameItem.details)
                            : nameItem.details;
                    setNameType(details.name_type || 1);
                }
                setItemTitle(nameItem.item_title || itemTypeList[1] || '');
                setFieldRequired(nameItem.field_required || false);
                setAnnotationText(nameItem.annotation_text || '');
            }
            setCommonConsts(getCommonConsts());

            // デバッグ用：ストア全体をコンソールに一度だけ出力
            const allData = getAllData();
        };

        // マウント時に一度だけ実行（ポーリングしない）
        updateData();
    }, [itemTypeList, propSelectedItem]);

    const handleChange = (value) => {
        const newNameType = parseInt(value);
        setNameType(newNameType);
        requestSave({ name_type: newNameType });
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
            console.warn('お名前項目が見つかりません');
            return;
        }
        const nameItem = getItemById(selectedItem.id);
        if (!nameItem) {
            console.warn('お名前項目が見つかりません');
            return;
        }

        // 現在の details を更新
        const currentDetails =
            typeof nameItem.details === 'string'
                ? JSON.parse(nameItem.details)
                : nameItem.details || {};

        const updatedDetails = overrides.name_type !== undefined
            ? { ...currentDetails, name_type: overrides.name_type }
            : currentDetails;

        // 保存処理を実行（ItemDetailPanel の buildPayload ロジックを簡易的に再現）
        const payload = {
            item_title: overrides.item_title !== undefined ? overrides.item_title : itemTitle,
            field_required: overrides.fieldRequired !== undefined ? (overrides.fieldRequired ? 1 : 0) : (fieldRequired ? 1 : 0),
            annotation_text: overrides.annotation_text !== undefined ? overrides.annotation_text : annotationText,
            name_type: updatedDetails.name_type || nameType,
        };

        saveItem(nameItem.id, payload);
    };

    return (
        <div className="space-y-4">
            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">
                    {itemTypeList[1] || `タイプ: ${1}`}
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
                            【姓と名を別々にするか】
                        </h4>
                        <div className="flex gap-4">
                            {Object.entries(commonConsts.NAME_SEPARATE_LIST || {}).map(
                                ([value, label]) => (
                                    <div key={value} className="custom-radio">
                                        <input
                                            type="radio"
                                            name="name_type"
                                            id={`name_type_${value}`}
                                            value={value}
                                            checked={parseInt(nameType) === parseInt(value)}
                                            onChange={(e) => handleChange(e.target.value)}
                                        />
                                        <label htmlFor={`name_type_${value}`} className="radio-label">
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

export default ItemTypeName;
