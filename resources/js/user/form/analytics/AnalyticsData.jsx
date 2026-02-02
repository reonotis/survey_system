import React from 'react';
import { Pie, Bar } from 'react-chartjs-2';

export function AnalyticsData({  widgetData, onClick }) {

    const labels = widgetData?.analytics_data?.labels ?? [];
    const counts = widgetData?.analytics_data?.count ?? [];

    const chartData = {
        labels,
        datasets: [
            {
                data: counts,
                backgroundColor: [
                    '#93c5fd', // blue-300
                    '#6ee7b7', // green-300
                    '#fca5a5', // red-300
                    '#fde68a', // yellow-300
                    '#c4b5fd', // violet-300
                    '#fdba74', // orange-300
                    '#5eead4', // teal-300
                    '#d8b4fe', // purple-300
                    '#f9a8d4', // pink-300
                    '#cbd5e1', // slate-300
                ]

            },
        ],
    };
    const renderGraph = () => {
        switch (widgetData.widget_setting.display_type) {
            case 1:
                return <span className="counter">{widgetData.analytics_data}<span className="unit">件</span></span>;
            case 2:
                return <span className="counter">{widgetData.analytics_data}<span className="unit">%</span></span>;
            case 3:
                return <Pie data={chartData} />;
            case 4:
                return (
                    <Bar
                        data={chartData}
                        options={{
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { precision: 0 },
                                },
                            },
                        }}
                    />
                );
            default:
                return null;
        }
    };

    return (
        <>
            <div className="widget-title flex-between-center">
                <div>{widgetData.title?? '　'}</div>
                <div className=" cursor-pointer" onClick={() => onClick(widgetData)}> 編集 </div>
            </div>
            <div className="widget-content">
                {widgetData && renderGraph()}
            </div>
        </>
    );
}

