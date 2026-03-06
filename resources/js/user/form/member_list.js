import $ from 'jquery';
import { defaultDataTableConfig } from '../../common/datatables.js';
import { modalConfig, openModal } from "../../common/modal.js";

document.addEventListener('DOMContentLoaded', function() {
    const table = $('#list_tbl');
    const deleteUrlTemplate = table.data('delete-url');

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
                data: 'name',
                title: '名前',
            }, {
                data: 'email',
                title: 'メールアドレス',
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



    // モーダル
    modalConfig({
        triggers: { '#add_btn': '#display_setting_modal' }
    });

    // バリデーションエラーで戻った場合はモーダルを表示
    if ($('#display_setting_modal').attr('data-open-on-load') === '1') {
        openModal('#display_setting_modal');
    }

});
