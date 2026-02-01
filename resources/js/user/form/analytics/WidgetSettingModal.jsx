import React from 'react';

export function WidgetSettingModal({ isOpen, onClose }) {
    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div className="bg-white rounded-md shadow-lg w-full max-w-lg p-6">
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-lg font-bold">ウィジェットを追加</h2>
                    <button
                        type="button"
                        className="text-gray-500 hover:text-gray-700 text-xl leading-none"
                        onClick={onClose}
                        aria-label="閉じる"
                    >
                        ×
                    </button>
                </div>
                <div className="mb-4">
                    {/* TODO: ここにウィジェット設定用フォームなどを配置します */}
                    <p>ウィジェット追加モーダルの内容をここに実装してください。</p>
                </div>
                <div className="flex justify-end gap-2">
                    <button
                        type="button"
                        className="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100"
                        onClick={onClose}
                    >
                        キャンセル
                    </button>
                    <button
                        type="button"
                        className="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
                        onClick={onClose}
                    >
                        保存
                    </button>
                </div>
            </div>
        </div>
    );
}

