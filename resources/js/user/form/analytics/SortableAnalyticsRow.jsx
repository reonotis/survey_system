import React from 'react';
import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { AnalyticsRow } from './AnalyticsRow';

/**
 * 応募分析ダッシュボードの行をドラッグ＆ドロップで並び替えるためのラッパーコンポーネント
 * - useSortable で行全体にトランスフォームとトランジションを適用
 * - ドラッグハンドル用の props を子コンポーネントに渡す
 */
export function SortableAnalyticsRow({ analyticsData, ...rowProps }) {
    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
        isDragging,
    } = useSortable({ id: analyticsData.id });

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
        width: '100%',
        zIndex: isDragging ? 10 : 0,
        opacity: isDragging ? 0 : 1, // DragOverlay で見せるため本体は透明に
    };

    return (
        <div ref={setNodeRef} style={style}>
            <AnalyticsRow
                analyticsData={analyticsData}
                dragHandleAttributes={attributes}
                dragHandleListeners={listeners}
                {...rowProps}
            />
        </div>
    );
}

