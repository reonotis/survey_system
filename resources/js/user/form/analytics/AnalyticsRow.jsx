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
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M9 3h6a1 1 0 0 1 1 1v2H8V4a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M4 6h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M6 6l1 15a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10 11v7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M14 11v7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

