import React from 'react';

function SelectableItemsList({ selectableItems }) {
    const handleDragStart = (e, itemType, itemName) => {
        const dragData = {
            itemType: parseInt(itemType),
            itemName: itemName
        };
        e.dataTransfer.setData('application/json', JSON.stringify(dragData));
        e.dataTransfer.effectAllowed = 'copy';
        // ドラッグ中の視覚効果
        e.currentTarget.style.opacity = '0.5';

    };

    const handleDragEnd = (e) => {
        e.currentTarget.style.opacity = '1';
    };

    return (
        <div>
            <h3 className="title-label">追加可能項目</h3>
            <div className="space-y-2">
                {Object.keys(selectableItems).length > 0 ? (
                    Object.entries(selectableItems).map(([itemType, itemName]) => (
                        <div
                            key={itemType}
                            draggable
                            onDragStart={(e) => handleDragStart(e, itemType, itemName)}
                            onDragEnd={handleDragEnd}
                            className="p-3 border border-gray-200 rounded hover:bg-gray-50 cursor-move transition-colors"
                        >
                            <p className="text-sm font-medium text-gray-700">{itemName}</p>
                        </div>
                    ))
                ) : (
                    <p className="text-sm text-gray-500 text-center py-4">
                        追加可能な項目がありません
                    </p>
                )}
            </div>
        </div>
    );
}

export default SelectableItemsList;

