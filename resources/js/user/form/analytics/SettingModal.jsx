import React from 'react';
import {WidgetAdd} from "./WidgetAdd.jsx";

/**
 * 設定モーダル
 * - isOpen: 表示/非表示
 * - setIsOpen: 呼び出し元の state を閉じるための setter
 * - selectedData: AnalyticsData から渡されたときのデータ（「+」ボタン経由のときは null）
 */
export function SettingModal({ isOpenCreateModal, formItems, closeModal, rowId, columnId, selectedData, urlWidgetAdd }) {
    if (!isOpenCreateModal) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div className="bg-white rounded-md shadow-lg w-full p-6"
                 style={{
                     width: 800,
                 }}
            >
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-lg font-bold">ウィジェットを追加</h2>
                    <button
                        type="button"
                        className="text-gray-500 hover:text-gray-700 text-xl leading-none"
                        aria-label="閉じる"
                        onClick={closeModal}
                    >×</button>
                </div>
                <div className="mb-4">
                    {selectedData ? (
                        <p className="mb-2">選択中のID: {selectedData.id}</p>
                    ) : (
                        <WidgetAdd
                            formItems={formItems}
                            rowId={rowId}
                            columnId={columnId}
                            urlWidgetAdd={urlWidgetAdd}/>
                    )}
                </div>
            </div>
        </div>
    );
}

