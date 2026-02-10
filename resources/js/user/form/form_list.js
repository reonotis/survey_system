import $ from 'jquery';
import { defaultDataTableConfig } from '../../common/datatables.js';
import { DropdownCheckboxHandler } from '../../common/DropdownCheckboxHandler.js';

document.addEventListener('DOMContentLoaded', function() {
    const table = $('#form_list_tbl');
    const formSettingURL = table.data('form-setting-url');
    const showUrlTemplate = table.data('show-url');
    const deleteUrlTemplate = table.data('delete-url');

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
                data: 'period',
                title: '期間',
                orderable: false,
            }, {
                data: 'count',
                name: 'count',
                title: '応募数',
                createdCell: function (td, cellData, rowData, row, col) {
                    td.classList.add('dt-right');
                },
            }, {
                data: 'owner_name',
                name: 'owner_name',
                title: 'フォームオーナー',
                orderable: false,
            }, {
                data: 'id',
                title: '設定/管理',
                orderable: false,
                createdCell: function (td, cellData, rowData, row, col) {
                    td.classList.add('dt-center');
                },
                render: function(data, type, row) {
                    const url = (formSettingURL || '').replace('__ID__', data);
                    return '<a href="' + url + '" class="btn min">設定</a>';
                }
            }, {
                data: 'id',
                title: '複製',
                orderable: false,
                createdCell: function (td, cellData, rowData, row, col) {
                    td.classList.add('dt-center');
                },
                render: function(data, type, row) {
                    return '<a href="" class="btn min">複製</a>';
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

    table.on('click', '.js-delete-form', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const deleteUrl = (deleteUrlTemplate || '').replace('__ID__', id);
        if (!deleteUrl) return;

        if (!window.confirm('本当に削除しますか？この操作は元に戻せません。')) {
            return;
        }

        const form = $('<form>').attr({
            method: 'POST',
            action: deleteUrl
        });
        form.append($('<input>').attr({
            type: 'hidden',
            name: '_token',
            value: $('meta[name="csrf-token"]').attr('content')
        }));
        form.appendTo('body').submit();
    });


    DropdownCheckboxHandler.setTextBox('status', 'status[]')
});
