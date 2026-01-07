import React, { useState } from 'react';
import AddItem from './AddItem';

function SelectableItemsList({ addableItems, onAddItem }) {
    // 追加しようとして選択した項目タイプ
    const [selectedItemType, setSelectedItemType] = useState(null);

    // 項目追加後にselectedItemTypeをリセットする
    const handleAddItemWithReset = (newItem) => {
        onAddItem(newItem);
        setSelectedItemType(null);
    };

    return (
        <div>
            <h3 className="title-label">追加可能項目</h3>

            <div className="space-y-2">
                {addableItems?.length > 0 ? (
                    addableItems.map(item => (
                        <AddItem
                            key={item.itemType}
                            item={item}
                            isSelected={selectedItemType === item.itemType}
                            onSelect={() => setSelectedItemType(item.itemType)}
                            onAdd={handleAddItemWithReset}
                        />
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
