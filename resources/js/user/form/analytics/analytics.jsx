import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import { SortableAnalyticsRow } from './SortableAnalyticsRow';
import { SettingModal } from './SettingModal';
import { DndContext, closestCenter, DragOverlay } from '@dnd-kit/core';
import {
    SortableContext,
    verticalListSortingStrategy,
    arrayMove,
} from '@dnd-kit/sortable';
import {
    Chart as ChartJS,
    ArcElement,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
} from 'chart.js';

ChartJS.register(
    ArcElement,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend
);

// 縦方向のみに移動を制限するローカル modifier
const restrictToVerticalAxis = ({ transform }) => {
    return {
        ...transform,
        x: 0,
    };
};

function ItemSettingReact({ analyticsList: initialAnalyticsList, formItems, urlWidgetAdd, urlAddWidgetRow, urlWidgetClear, urlUpdateRowOrder, widgetTypeList }) {
    const [analyticsList, setAnalyticsList] = useState(initialAnalyticsList);
    const [isOpenCreateModal, setIsOpenCreateModal] = useState(false);
    const [rowId, setRowId] = useState(null);
    const [columnId, setColumnId] = useState(null);
    const [selectWidget, setSelectWidget] = useState(null);
    const [activeRowId, setActiveRowId] = useState(null);

    /**
     * モーダルを開く
     * - 「+」ボタンから呼ばれた場合: data なしで開く
     * - AnalyticsData から呼ばれた場合: 対象データ付きで開く
     */
    const openCreateWidgetSettingModal = (rowId, columnId) => {
        setRowId(rowId);
        setColumnId(columnId);
        setIsOpenCreateModal(true);
    };

    const handleRowDelete = (deletedRowId) => {
        setAnalyticsList((prev) => prev.filter((row) => row.id !== deletedRowId));
    };

    const handleRowDragEnd = (event) => {
        const { active, over } = event;
        if (!over || active.id === over.id) {
            setActiveRowId(null);
            return;
        }

        setAnalyticsList((prev) => {
            const oldIndex = prev.findIndex((row) => row.id === active.id);
            const newIndex = prev.findIndex((row) => row.id === over.id);
            if (oldIndex === -1 || newIndex === -1) return prev;

            const sorted = arrayMove(prev, oldIndex, newIndex);

            // 並び替え結果をサーバーに保存
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
            const rowIds = sorted.map((row) => row.id);
            fetch(urlUpdateRowOrder, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ row_ids: rowIds }),
            }).catch(() => {
                // サーバーエラー時は画面リロードで整合性を保つ
                alert('行の並び順の保存中にエラーが発生しました。画面をリロードします。');
                window.location.reload();
            });

            return sorted;
        });
        setActiveRowId(null);
    };

    const handleWidgetClear = async (rowId, columnId) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        try {
            const res = await fetch(urlWidgetClear, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    row_id: rowId,
                    column_id: columnId,
                }),
            });
            if (!res.ok) throw new Error('API error');

            const data = await res.json();
            if (!data.success) throw new Error('API error');

            setAnalyticsList((prev) =>
                prev.map((row) => {
                    if (row.id !== rowId) return row;
                    const key = `analytics_dashboard_widget_id_${columnId}`;
                    return {
                        ...row,
                        [key]: null,
                    };
                }),
            );
        } catch (e) {
            alert('エラーが発生しました。画面をリロードします');
            window.location.reload();
        }
    };

    const registerWidgetRow = async (layout_type) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        try {
            const res = await fetch(urlAddWidgetRow, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    layout_type: layout_type
                }),
            });
            if (!res.ok) throw new Error('API error');

            const data = await res.json();
            if (!data.success) throw new Error('API error');

            if (data.row) {
                setAnalyticsList((prev) => [...prev, data.row]);
            }

        } catch (e) {
            alert('エラーが発生しました。画面をリロードします')
            window.location.reload();
        }
    };

    const closeModal = () => {
        setIsOpenCreateModal(false);
    };

    return (
        <>
            <DndContext
                collisionDetection={closestCenter}
                onDragStart={({ active }) => setActiveRowId(active.id)}
                onDragEnd={handleRowDragEnd}
                onDragCancel={() => setActiveRowId(null)}
                modifiers={[restrictToVerticalAxis]}
            >
                <SortableContext
                    items={analyticsList.map((row) => row.id)}
                    strategy={verticalListSortingStrategy}
                >
                    {analyticsList.map((item, index) => (
                        <SortableAnalyticsRow
                            key={item.id ?? index}
                            analyticsData={item}
                            openCreateWidgetSettingModal={openCreateWidgetSettingModal}
                            onRowDelete={handleRowDelete}
                            onWidgetClear={handleWidgetClear}
                        />
                    ))}
                </SortableContext>

                <DragOverlay>
                    {activeRowId != null && (() => {
                        const row = analyticsList.find((r) => r.id === activeRowId);
                        if (!row) return null;
                        return (
                            <div style={{ width: '100%' }}>
                                <SortableAnalyticsRow
                                    analyticsData={row}
                                    openCreateWidgetSettingModal={openCreateWidgetSettingModal}
                                    onRowDelete={handleRowDelete}
                                    onWidgetClear={handleWidgetClear}
                                />
                            </div>
                        );
                    })()}
                </DragOverlay>
            </DndContext>

            {/* 行を追加させる分割用選択技 */}
            <div className="dashboard original-dashboard">
                <div className="widget add-widget p-2" style={{gridColumn: `span 62` }}>
                    <div className="flex-center-center h-full w-full mb-2">追加する構造を選択してください</div>
                    <div className="flex-center-center flex-wrap gap-4 h-full w-full">
                        {Object.entries(widgetTypeList || {}).map(([type, data]) => (
                            <div
                                key={type}
                                className="cursor-pointer flex flex-col items-center gap-1 border border-gray-300 p-2 rounded"
                                onClick={() => registerWidgetRow(Number(type))}
                            >
                                {/* 画像 */}
                                <img
                                    src={`/image/${data.image_file}`}
                                    alt={data.label}
                                    className="w-40 object-contain"
                                />

                                {/* ラベル */}
                                <div className="text-sm text-center">
                                    {data.label}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            <SettingModal
                isOpenCreateModal={isOpenCreateModal}
                formItems={formItems}
                closeModal={closeModal}
                rowId={rowId}
                columnId={columnId}
                selectedData={selectWidget}
                urlWidgetAdd={urlWidgetAdd}
            />
        </>
    );
}

const container = document.getElementById('react-analytics-container');
if (container) {
    createRoot(container).render(
        <ItemSettingReact
            analyticsList={JSON.parse(container.dataset.analytics || '[]')}
            formItems={JSON.parse(container.dataset.formItems || '[]')}
            urlWidgetAdd={JSON.parse(container.dataset.urlWidgetAdd || '')}
            urlAddWidgetRow={JSON.parse(container.dataset.urlAddWidgetRow || '')}
            urlWidgetClear={JSON.parse(container.dataset.urlWidgetClear || '')}
            urlUpdateRowOrder={JSON.parse(container.dataset.urlUpdateRowOrder || '')}
            widgetTypeList={JSON.parse(container.dataset.widgetTypeList || '{}')}
        />
    );
}
