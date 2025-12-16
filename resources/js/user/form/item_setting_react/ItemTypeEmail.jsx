import React, { useState, useEffect } from 'react';
import { saveItem, getItemById, getCommonConsts } from './itemSettingData';

/**
 * 基本的な項目設定（itemTitle、fieldRequired、annotationText）
 * メール、電話、性別、住所などの項目タイプで使用
 *
 * - グローバルな itemSettingData ストアを直接参照して表示・保存します。
 * - 画面右側の詳細設定に依存しないため、どこからでも同じ状態を参照できます。
 */
function ItemTypeEmail({itemTypeList, itemType, selectedId}) {
    const [itemTitle, setItemTitle] = useState('');
    const [fieldRequired, setFieldRequired] = useState(false);
    const [annotationText, setAnnotationText] = useState('');
    const [commonConsts, setCommonConsts] = useState({});
    // 確認用メールアドレスの選択状態（checked 用）
    const [confirmFlg, setConfirmFlg] = useState('');

    // データストアから最新のデータを取得
    useEffect(() => {
        const updateData = () => {
            const selectedItem = getItemById(selectedId);

            if (selectedItem) {
                setItemTitle(selectedItem.item_title || itemTypeList[itemType] || '');
                setFieldRequired(selectedItem.field_required || false);
                setAnnotationText(selectedItem.annotation_text || '');
                // details から confirm_flg を取得（文字列/オブジェクト両対応）
                let details = selectedItem.details;
                if (details) {
                    const parsedDetails =
                        typeof details === 'string' ? JSON.parse(details) : details;
                    if (parsedDetails && parsedDetails.confirm_flg !== undefined) {
                        setConfirmFlg(String(parsedDetails.confirm_flg));
                    }
                }
                // details に無い場合はトップレベルも確認
                if (selectedItem.confirm_flg !== undefined && selectedItem.confirm_flg !== null) {
                    setConfirmFlg(String(selectedItem.confirm_flg));
                }
            }
            console.log(selectedItem)
            setCommonConsts(getCommonConsts());
        };

        // マウント時とselectedIdが変更されたときに実行
        updateData();
    }, [itemTypeList, itemType, selectedId]);


    const handleFieldRequiredChange = (e) => {
        const checked = e.target.checked;
        setFieldRequired(checked);
        saveItem(selectedId, { field_required: checked ? 1 : 0 });
    };

    return (
        <div className="space-y-4">
            <div className="border-b pb-3">
                <p className="text-sm font-medium text-gray-800">
                    {itemTypeList[itemType] || 'メールアドレス'}
                </p>
                <p className="text-xs text-gray-500 mt-1">
                    <input
                        type="text"
                        className="input-box w-full"
                        value={itemTitle}
                        onChange={(e) => setItemTitle(e.target.value)}
                        onBlur={() => saveItem(selectedId, { item_title: itemTitle })}
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
                        onBlur={() => saveItem(selectedId, { annotation_text: annotationText })}
                    />
                </div>
                <div>
                    <h4 className="text-sm font-semibold text-gray-700 mb-2">確認用のメールアドレス項目を設ける</h4>
                    <div className="flex gap-4">
                        {Object.entries(commonConsts.EMAIL_CONFIRM_LIST || {}).map(
                            ([value, label]) => (
                                <div key={value} className="custom-radio">
                                    <input
                                        type="radio"
                                        name="confirm_flg"
                                        id={`confirm_flg_${value}`}
                                        value={value}
                                        checked={String(confirmFlg) === String(value)}
                                        onChange={(e) => {
                                            const v = e.target.value;
                                            // 画面の checked 状態更新
                                            setConfirmFlg(v);
                                            // 既存の saveItem 呼び出し（処理内容は変更しない）
                                            saveItem(selectedId, { confirm_flg: v });
                                        }}
                                    />
                                    <label htmlFor={`confirm_flg_${value}`} className="radio-label">
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
    );
}

export default ItemTypeEmail;

