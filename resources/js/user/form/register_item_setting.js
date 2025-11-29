import $ from 'jquery';
import { setupConditionalToggle } from '../../common/toggleHandler.js';

// ドキュメント読み込み後にモーダル設定を初期化
$(document).ready(function() {

    // - 項目種別が お名前(item_type = 1) のときだけ、#name_details_row行を表示します。
    // - 条件は toggleHandler の汎用関数に渡します。anyOf: ['1'] は「値が1ならOK」を意味します。
    setupConditionalToggle('#name_details_row', [
        { selector: '#item_add_modal input[name="new_item_type"]', anyOf: ['1'] }
    ], { logic: 'AND', initialEval: true });

    setupConditionalToggle('#yomi_details_row', [
        { selector: '#item_add_modal input[name="new_item_type"]', anyOf: ['2'] }
    ], { logic: 'AND', initialEval: true });

    setupConditionalToggle('#email_details_row', [
        { selector: '#item_add_modal input[name="new_item_type"]', anyOf: ['3'] }
    ], { logic: 'AND', initialEval: true });

    setupConditionalToggle('#tel_details_row', [
        { selector: '#item_add_modal input[name="new_item_type"]', anyOf: ['4'] }
    ], { logic: 'AND', initialEval: true });

    setupConditionalToggle('#gender_details_row', [
        { selector: '#item_add_modal input[name="new_item_type"]', anyOf: ['5'] }
    ], { logic: 'AND', initialEval: true });

    setupConditionalToggle('#address_details_row', [
        { selector: '#item_add_modal input[name="new_item_type"]', anyOf: ['6'] }
    ], { logic: 'AND', initialEval: true });



});
