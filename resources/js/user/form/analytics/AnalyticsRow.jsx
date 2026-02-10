import React from 'react';
import { AnalyticsData } from './AnalyticsData';

export function AnalyticsRow({ analyticsData, openCreateWidgetSettingModal, onRowDelete }) {

    const handleDeleteRow = async () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        try {
            const urlWidgetRowDelete = window.urlWidgetRowDelete;
            const res = await fetch(urlWidgetRowDelete, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ id: analyticsData.id }),
            });
            if (!res.ok) throw new Error('API error');

            const data = await res.json();
            if (!data.success) throw new Error('API error');

            onRowDelete?.(analyticsData.id);
        } catch (e) {
            alert('エラーが発生しました。画面をリロードします');
            window.location.reload();
        }
    };


    return (
        <>
            <div className="dashboard-row mb-2">
                {analyticsData.layout_definition.map((value, index) => {
                    const widget_number = index + 1;
                    const hasWidget = analyticsData[`analytics_dashboard_widget_id_${widget_number}`];

                    return (
                        <div key={`${analyticsData.id}-${index}`} className="widget" style={{ gridColumn: `span ${value}` }}>
                            {hasWidget ? (
                                <AnalyticsData widgetData={hasWidget} onClick={openCreateWidgetSettingModal} />
                            ) : (
                                <button
                                    type="button"
                                    className="flex-center-center h-full w-full text-3xl font-bold"
                                    id="add_widget"
                                    onClick={() => openCreateWidgetSettingModal(analyticsData.id, index + 1)}
                                >+</button>
                            )}
                        </div>
                    );
                })}
                <div className="widget" style={{ gridColumn: `span 1` }}>
                    <div className="flex-between-center flex-col h-full" >
                        <div className="" >
                            ☰
                        </div>
                        <div>
                            <button
                                type="button"
                                onClick={handleDeleteRow}
                            >
                                <img src="/icon/delete.svg" width="20" height="20" alt="" aria-hidden="true" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

