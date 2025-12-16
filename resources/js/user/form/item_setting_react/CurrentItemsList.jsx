import React, { useState } from 'react';

function DropZone({ onDrop, index }) {
    const [isDraggingOver, setIsDraggingOver] = useState(false);

    const handleDragOver = (e) => {
        e.preventDefault();
        e.stopPropagation();
        e.dataTransfer.dropEffect = 'copy';
        setIsDraggingOver(true);
    };

    const handleDragLeave = (e) => {
        e.preventDefault();
        e.stopPropagation();
        setIsDraggingOver(false);
    };

    const handleDrop = (e) => {
        e.preventDefault();
        e.stopPropagation();
        setIsDraggingOver(false);

        try {
            const data = JSON.parse(e.dataTransfer.getData('application/json'));
            if (data.itemType && onDrop) {
                onDrop(data.itemType, index);
            }
        } catch (error) {
            console.error('ドロップデータの解析に失敗しました:', error);
        }
    };

    return (
        <div
            className={`h-2 transition-all ${
                isDraggingOver
                    ? 'h-8 bg-blue-200 border-2 border-blue-400 border-dashed rounded my-1'
                    : 'h-1 hover:h-2 hover:bg-blue-100'
            }`}
            onDragOver={handleDragOver}
            onDragLeave={handleDragLeave}
            onDrop={handleDrop}
        />
    );
}

function CurrentItemsList({ formItemsList, itemTypeList, onItemClick, selectedItemId, onDropItem }) {
    const [isDraggingOver, setIsDraggingOver] = useState(false);

    // オブジェクトを配列に変換（sort順でソート）
    const itemsArray = Array.isArray(formItemsList)
        ? formItemsList
        : Object.values(formItemsList).sort((a, b) => (a.sort || 0) - (b.sort || 0));

    const handleDragOver = (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
        setIsDraggingOver(true);
    };

    const handleDragLeave = (e) => {
        e.preventDefault();
        setIsDraggingOver(false);
    };

    const handleDrop = (e) => {
        e.preventDefault();
        setIsDraggingOver(false);

        // 項目が空の場合は、子要素のonDropで処理されるため、ここでは処理をスキップ
        if (itemsArray.length === 0) {
            return;
        }

        try {
            const data = JSON.parse(e.dataTransfer.getData('application/json'));
            if (data.itemType && onDropItem) {
                // 最後に追加
                onDropItem(data.itemType, itemsArray.length);
            }
        } catch (error) {
            console.error('ドロップデータの解析に失敗しました:', error);
        }
    };

    const handleDropInZone = (itemType, index) => {
        if (onDropItem) {
            onDropItem(itemType, index);
        }
    };

    return (
        <div>
            <h3 className="text-lg font-semibold mb-4 text-gray-800">現在設定中の項目</h3>
            <div
                className={`space-y-0 max-h-96 overflow-y-auto min-h-[200px] transition-colors ${
                    isDraggingOver ? 'bg-blue-50' : ''
                }`}
                onDragOver={handleDragOver}
                onDragLeave={handleDragLeave}
                onDrop={handleDrop}
            >
                {itemsArray && itemsArray.length > 0 ? (
                    <>
                        {/* 最初のドロップゾーン */}
                        <DropZone onDrop={handleDropInZone} index={0} />
                        {itemsArray.map((item, index) => (
                            <React.Fragment key={item.id}>
                                <div
                                    onClick={() => onItemClick && onItemClick(item.id)}
                                    className={`p-3 border rounded hover:bg-blue-50 cursor-pointer transition-colors ${
                                        selectedItemId === item.id
                                            ? 'border-blue-500 bg-blue-50'
                                            : 'border-gray-200'
                                    }`}
                                >
                                    <div className="flex items-center justify-between">
                                        <div className="flex-1">
                                            <p className="text-sm font-medium text-gray-800">
                                                {item.item_title || itemTypeList[item.item_type] || '項目'}
                                            </p>
                                            <p className="text-xs text-gray-500 mt-1">
                                                {itemTypeList[item.item_type] || `タイプ: ${item.item_type}`}
                                            </p>
                                        </div>
                                        {item.field_required && (
                                            <span className="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded">
                                                必須
                                            </span>
                                        )}
                                    </div>
                                </div>
                                {/* 各項目の後のドロップゾーン */}
                                <DropZone onDrop={handleDropInZone} index={index + 1} />
                            </React.Fragment>
                        ))}
                    </>
                ) : (
                    <div
                        className="min-h-[200px] flex items-center justify-center border-2 border-dashed border-gray-300 rounded"
                        onDragOver={(e) => {
                            e.preventDefault();
                            e.dataTransfer.dropEffect = 'copy';
                        }}
                        onDrop={(e) => {
                            e.preventDefault();
                            e.stopPropagation(); // 親要素へのイベント伝播を防ぐ
                            try {
                                const data = JSON.parse(e.dataTransfer.getData('application/json'));
                                if (data.itemType && onDropItem) {
                                    onDropItem(data.itemType, 0);
                                }
                            } catch (error) {
                                console.error('ドロップデータの解析に失敗しました:', error);
                            }
                        }}
                    >
                        <p className="text-sm text-gray-500 text-center">
                            設定中の項目がありません<br />
                            左側から項目をドラッグして追加してください
                        </p>
                    </div>
                )}
            </div>
        </div>
    );
}

export default CurrentItemsList;

