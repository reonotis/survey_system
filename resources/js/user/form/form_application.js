import $ from 'jquery';
import {defaultDataTableConfig} from '../../common/datatables.js';
import {modalConfig} from "../../common/modal.js";
import {initSortable} from '../../common/sortable.js';

// ------------------------------
// Utilities
// ------------------------------
function parseDynamicColumns(datasetJson) {
    try {
        const parsed = JSON.parse(datasetJson || '[]');
        return Array.isArray(parsed) ? parsed : [];
    } catch (_) {
        return [];
    }
}

function buildColumns(dynamicColumns) {
    // 先頭に申込日時を固定し、以後に有効な動的列を追加
    const columns = [{ data: 'created_at_text', name: 'created_at', title: '申込日時' }];

    for (let i = 0; i < dynamicColumns.length; i++) {
        const col = dynamicColumns[i];
        if (!col || typeof col !== 'object') continue;
        if (!col.data || !col.name) continue;
        columns.push({
            data: String(col.data),
            name: String(col.name),
            title: col.title ?? '',
            defaultContent: col.defaultContent ?? ''
        });
    }

    console.log(columns)
    return columns;
}

function recreateTableNode($table) {
    // 既存インスタンスを破棄し、テーブルノードを新規作成して状態をリセット
    if ($.fn.DataTable.isDataTable($table)) {
        $table.DataTable().clear().destroy(true);
    }

    const parent = $table.parent();
    const urlAttr = $table.data('url');

    const $newTable = $('<table></table>')
        .attr('id', 'application_list_tbl')
        .addClass('list-tbl')
        .attr('data-url', urlAttr);

    $table.remove();
    parent.prepend($newTable);
    return $('#application_list_tbl');
}

function mountHeader($table, columns) {
    const theadHtml = `<thead><tr>${columns.map(c => `<th>${c.title ?? ''}</th>`).join('')}</tr></thead><tbody></tbody>`;
    $table.html(theadHtml);
}

function initDataTable($table, columns) {
    const thCount = $table.find('thead th').length;
    const validColumns = (columns || []).filter(c => c && typeof c === 'object' && 'data' in c);
    const initColumns = thCount === validColumns.length ? validColumns : validColumns.slice(0, thCount);

    $table.DataTable({
        ...defaultDataTableConfig,
        destroy: true,
        order: [[0, 'desc']],
        ajax: {
            url: $table.data('url'),
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: initColumns,
    });
}

// ------------------------------
// Entry
// ------------------------------
document.addEventListener('DOMContentLoaded', function () {
    // Sortable
    initSortable('#form-items-sortable');

    // モーダル
    modalConfig({
        triggers: { '#item_add_btn': '#display_setting_modal' }
    });

    // 応募一覧表
    const originalTable = $('#application_list_tbl');
    const datasetJson = originalTable.attr('data-columns') || '[]';

    const dynamicColumns = parseDynamicColumns(datasetJson);
    const columns = buildColumns(dynamicColumns);

    const $freshTable = recreateTableNode(originalTable);
    mountHeader($freshTable, columns);
    initDataTable($freshTable, columns);
});

