import $ from 'jquery';
import { defaultDataTableConfig } from '../../common/datatables.js';

document.addEventListener('DOMContentLoaded', function() {
    const table = $('#form_list_tbl');
    const showUrlTemplate = table.data('show-url');

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
                title: '応募数',
            }, {
                data: 'publication_status_text',
                name: 'publication_status',
                title: '状態',
                render: function(data, type, row) {
                    return '0';
                }
            }, {
                data: 'id',
                title: '管理',
                orderable: false,
                className: 'dt-center',
                render: function(data, type, row) {
                    const url = (showUrlTemplate || '').replace('__ID__', data);
                    return '<a href="' + url + '" class="btn min">管理</a>';
                }
            }
        ]
    });
});
