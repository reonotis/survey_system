import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import { AnalyticsData } from './AnalyticsData';
import { AnalyticsRow } from './AnalyticsRow';
import { SettingModal } from './SettingModal';
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

function ItemSettingReact({ analyticsList: initialAnalyticsList, formItems, urlWidgetAdd, urlAddWidgetRow, widgetTypeList }) {
    const [analyticsList, setAnalyticsList] = useState(initialAnalyticsList);
    const [isOpenCreateModal, setIsOpenCreateModal] = useState(false);
    const [rowId, setRowId] = useState(null);
    const [columnId, setColumnId] = useState(null);
    const [selectWidget, setSelectWidget] = useState(null);

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
            {analyticsList.map((item, index) => (
                <AnalyticsRow
                    key={item.id ?? index}
                    analyticsData={item}
                    openCreateWidgetSettingModal={openCreateWidgetSettingModal}
                    onRowDelete={handleRowDelete}
                />
            ))}
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
            widgetTypeList={JSON.parse(container.dataset.widgetTypeList || '{}')}
        />
    );
}
