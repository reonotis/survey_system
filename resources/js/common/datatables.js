import 'datatables.net';
import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

// 日本語化設定
export const japaneseLanguage = {
    "sProcessing": "処理中...",
    "sLengthMenu": "_MENU_ 件表示",
    "sZeroRecords": "データはありません",
    "sInfo": "_TOTAL_ 件中 _START_ から _END_ まで表示",
    "sInfoEmpty": "0 件中 0 から 0 まで表示",
    "sInfoFiltered": "（全 _MAX_ 件より抽出）",
    "sInfoPostFix": "",
    "sSearch": "検索:",
    "sUrl": "",
    "oPaginate": {
        "sFirst": "最初",
        "sPrevious": "前",
        "sNext": "次",
        "sLast": "最後"
    }
};

// 共通のDataTables設定
export const defaultDataTableConfig = {
    language: japaneseLanguage,
    pageLength: 10,
    responsive: true,
    processing: true,
    serverSide: true,
    dom: '<"top"lp>rt<"bottom"ip><"clear">',
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
};