import $ from 'jquery';
import { setupConditionalToggle } from '../../common/toggleHandler.js';

// ドキュメント読み込み後にモーダル設定を初期化
$(document).ready(function() {

    // 通知メールで「利用する」(値:1)を選択したときにを表示
    [
        '#notification_mail_title_row',
        '#notification_mail_message_row',
        '#notification_mail_address_row'
    ].forEach((selector) => {
        setupConditionalToggle(selector, [
            { selector: 'input[name="notification_mail_flg"]', anyOf: ['1'] }
        ], { logic: 'AND', initialEval: true });
    });

    // 自動返信メールで「利用する」(値:1)を選択したときに表示
    [
        '#auto_reply_mail_title_row',
        '#auto_reply_mail_message_row'
    ].forEach((selector) => {
        setupConditionalToggle(selector, [
            { selector: 'input[name="auto_reply_mail_flg"]', anyOf: ['1'] }
        ], { logic: 'AND', initialEval: true });
    });

});
