import $ from 'jquery';

import tinymce, { initTinymce } from "../../common/tinymce.js";
import { setupConditionalToggle } from '../../common/toggleHandler.js';

initTinymce({
    selector: 'textarea[name="auto_reply_mail_message"]',
    height: 500,
});

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
        '#auto_reply_mail_template',
        '#auto_reply_mail_title_row',
        '#auto_reply_mail_message_row'
    ].forEach((selector) => {
        setupConditionalToggle(selector, [
            { selector: 'input[name="auto_reply_mail_flg"]', anyOf: ['1'] }
        ], { logic: 'AND', initialEval: true });
    });

});
// テンプレート選択時にAPIから取得
const templateSelect = document.querySelector('[name="auto_reply_mail_template"]');

if (templateSelect) {

    templateSelect.addEventListener('change', function () {

        const templateId = this.value;
        if (!templateId) return;

        fetch(`/user/mail-template/get-template/${templateId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),
                'Accept': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {

                const titleInput = document.querySelector('[name="auto_reply_mail_title"]');
                const messageTextarea = document.querySelector('[name="auto_reply_mail_message"]');

                if (titleInput) {
                    titleInput.value = data.subject ?? '';
                }

                if (messageTextarea) {
                    messageTextarea.value = data.body ?? '';
                }
                if (window.tinymce) {
                    const editor = tinymce.get(messageTextarea.id);
                    if (editor) {
                        editor.setContent(data.body ?? '');
                    }
                }

            })
            .catch(() => alert('テンプレート取得に失敗しました'));

    });

}
