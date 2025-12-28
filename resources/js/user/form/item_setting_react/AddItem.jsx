import React from 'react';

function AddItem({ item, isSelected, onSelect, onAdd }) {

    // 仮の Ajax 処理
    const handleAdd = async (e) => {
        e.stopPropagation(); // ← 親の onClick(onSelect) を止める

        try {
            const response = await fetch(window.draftAddItemUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                },
                body: JSON.stringify({
                    item_type: item.itemType,
                }),
            });

            // 仮レスポンス処理
            const data = await response.json();

            // 返却された項目を追加
            onAdd(data.form_item_draft);
        } catch (error) {
            console.error('追加失敗', error);
            alert('追加に失敗しました');
        }
    };

    return (
        <div
            onClick={onSelect}
            className={`
                p-3 border rounded bg-white transition cursor-pointer
                ${isSelected ? 'border-blue-500 bg-blue-50' : 'border-gray-200'}
            `}
        >
            <p className="text-sm font-medium text-gray-700">
                {item.label}
            </p>

            {/* 選択中の時だけ表示 */}
            {isSelected && (
                <div  className="mt-2 flex gap-2" >
                    <button
                        type="button"
                        onClick={handleAdd}
                        className="px-2 py-1 text-xs rounded bg-blue-500 text-white"
                    >
                        追加
                    </button>
                </div>
            )}
        </div>
    );
}

export default AddItem;
