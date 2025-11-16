import $ from 'jquery';
import { defaultDataTableConfig } from '../common/datatables.js';

document.addEventListener('DOMContentLoaded', function() {
    const table = $('#survey_list_tbl');
    const editUrlTemplate = table.data('edit-url');

    table.DataTable({
        ...defaultDataTableConfig,
        ajax: {
            url: table.data('url'),
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [
            {
                data: 'form_name',
                title: '管理名',
            }, {
                data: 'title',
                title: 'タイトル',
            }, {
                data: 'period',
                title: '期間',
                orderable: false,
            }, {
                data: 'publication_status_text',
                name: 'publication_status',
                title: '状態',
            }, {
                data: 'billing_month',
                name: 'billing_month',
                title: '請求月',
            }, {
                data: 'billing_status_text',
                name: 'billing_status',
                title: '請求状態',
            }, {
                data: 'id',
                title: '編集',
                orderable: false,
                className: 'dt-center',
                render: function(data, type, row) {
                    const url = (editUrlTemplate || '').replace('__ID__', data);
                    return '<a href="' + url + '" class="btn min">編集</a>';
                }
            }
        ]
    });
});
