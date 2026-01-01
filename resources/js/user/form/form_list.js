import $ from 'jquery';
import { defaultDataTableConfig } from '../../common/datatables.js';

document.addEventListener('DOMContentLoaded', function() {
    const table = $('#form_list_tbl');
    const formSettingURL = table.data('form-setting-url');
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
                data: 'count',
                name: 'count',
                title: '応募数',
            }, {
                data: 'id',
                title: 'フォーム設定',
                orderable: false,
                className: 'dt-center',
                render: function(data, type, row) {
                    const url = (formSettingURL || '').replace('__ID__', data);
                    return '<a href="' + url + '" class="btn min">設定</a>';
                }
            }, {
                data: 'id',
                title: '分析',
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
