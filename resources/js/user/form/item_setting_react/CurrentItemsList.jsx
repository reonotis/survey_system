import React, { useEffect, useState } from 'react';
import { DndContext, closestCenter } from '@dnd-kit/core';
import {
    SortableContext,
    verticalListSortingStrategy,
    arrayMove,
} from '@dnd-kit/sortable';

import Item from './Item';

function CurrentItemsList({ draftFormItems, onSortEnd, setEditingItemId, itemDelete }) {
    const [items, setItems] = useState([]);

    // props → state 同期
    useEffect(() => {
        setItems(draftFormItems ?? []);
    }, [draftFormItems]);

    const handleDragEnd = (event) => {
        const { active, over } = event;
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
                onDragEnd={handleDragEnd}
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
                                />
                            ))
                        ) : (
                            <p className="text-sm text-gray-500 text-center py-6">
                                左側の「追加可能項目」から、追加したい項目を選択してください。
                            </p>
                        )}
                    </div>
                </SortableContext>
            </DndContext>
        </div>
    );
}

export default CurrentItemsList;
