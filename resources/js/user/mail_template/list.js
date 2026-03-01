import $ from 'jquery';
import { defaultDataTableConfig } from '../../common/datatables.js';

document.addEventListener('DOMContentLoaded', function() {
    const table = $('#template_list_tbl');
    const upsertURL = table.data('upsert-url');

    const dataTable = table.DataTable({
        ...defaultDataTableConfig,
        ajax: {
            url: table.data('url'),
            type: 'POST',
            data: function (d) {
                return {
                    ...d,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                };
            }
        },
        columns: [
            {
                data: 'template_name',
                title: 'テンプレート名',
            }, {
                data: 'subject',
                title: '題名',
            }, {
                data: 'id',
                title: '設定/管理',
                orderable: false,
                createdCell: function (td, cellData, rowData, row, col) {
                    td.classList.add('dt-center');
                },
                render: function(data, type, row) {
                    return '<a href="' + upsertURL + '?template_id=' + data + '" class="btn min">設定</a>';
                }
            }, {
                data: 'id',
                title: '削除',
                orderable: false,
                createdCell: function (td, cellData, rowData, row, col) {
                    td.classList.add('dt-center');
                },
                render: function(data, type, row) {
                    return `
                        <div class="flex justify-center">
                            <button type="button" class="js-delete-form inline-flex items-center p-0 border-0 bg-transparent cursor-pointer" data-id="${data}" title="削除">
                                <img src="/icon/delete.svg" width="25" height="25" alt="削除">
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });

    $('#form_list_search_btn').on('click', function () {
        dataTable.ajax.reload();
    });

    table.on('click', '.js-delete-form', function(e) {
    });

});
