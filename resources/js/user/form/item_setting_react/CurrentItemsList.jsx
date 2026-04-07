import React, { useEffect, useState } from 'react';
import { DndContext, closestCenter, DragOverlay } from '@dnd-kit/core';
import {
    SortableContext,
    verticalListSortingStrategy,
    arrayMove,
} from '@dnd-kit/sortable';

import Item, { ItemDragPreview } from './Item';

// analytics.jsx と同様: 横方向のズレを防ぐ
const restrictToVerticalAxis = ({ transform }) => ({
    ...transform,
    x: 0,
});

function CurrentItemsList({ draftFormItems, onSortEnd, setEditingItemId, itemDelete, selectedItem }) {
    const [items, setItems] = useState([]);
    const [activeItemId, setActiveItemId] = useState(null);

    // props → state 同期
    useEffect(() => {
        setItems(draftFormItems ?? []);
    }, [draftFormItems]);

    const handleDragEnd = (event) => {
        const { active, over } = event;
        setActiveItemId(null);
        if (!over || active.id === over.id) return;

        setItems((prev) => {
            const oldIndex = prev.findIndex(i => i.id === active.id);
            const newIndex = prev.findIndex(i => i.id === over.id);

            const sorted = arrayMove(prev, oldIndex, newIndex);

            onSortEnd?.(sorted);

            return sorted;
        });
    };

    return (
        <div>
            <h3 className="title-label">現在設定中の項目</h3>

            <DndContext
                collisionDetection={closestCenter}
                onDragStart={({ active }) => setActiveItemId(active.id)}
                onDragEnd={handleDragEnd}
                onDragCancel={() => setActiveItemId(null)}
                modifiers={[restrictToVerticalAxis]}
            >
                <SortableContext
                    items={items.map(item => item.id)}
                    strategy={verticalListSortingStrategy}
                >
                    <div className="space-y-2 min-h-[200px] p-2 border rounded border-gray-200">
                        {items.length > 0 ? (
                            items.map(item => (
                                <Item
                                    key={item.id}
                                    item={item}
                                    onClick={() => setEditingItemId(item.id)}
                                    itemDelete={itemDelete}
                                    isSelected={selectedItem?.id === item.id} // ★ここ
                                />
                            ))
                        ) : (
                            <p className="text-sm text-gray-500 text-center py-6">
                                左側の「追加可能項目」から、追加したい項目を選択してください。
                            </p>
                        )}
                    </div>
                </SortableContext>

                <DragOverlay>
                    {activeItemId != null && (() => {
                        const row = items.find((i) => i.id === activeItemId);
                        if (!row) return null;
                        return (
                            <div style={{ width: '100%' }}>
                                <ItemDragPreview item={row} />
                            </div>
                        );
                    })()}
                </DragOverlay>
            </DndContext>
        </div>
    );
}

export default CurrentItemsList;
