import $ from 'jquery';
import '../../scss/common/modal.scss';

/**
 * モーダルを開く
 * @param {string|jQuery} modalSelector - モーダルのセレクター（例: '.modal-background' または特定のID）
 */
export function openModal(modalSelector) {
    const $modal = $(modalSelector);
    if ($modal.length === 0) {
        console.error(`Modal not found: ${modalSelector}`);
        return;
    }
    $modal.fadeIn(200);
}

/**
 * モーダルを閉じる
 * @param {string|jQuery} modalSelector - モーダルのセレクター
 */
export function closeModal(modalSelector) {
    const $modal = $(modalSelector);
    if ($modal.length === 0) {
        console.error(`Modal not found: ${modalSelector}`);
        return;
    }
    $modal.fadeOut(200);
}

/**
 * モーダルの設定を初期化
 * @param {Object} config - 設定オブジェクト
 * @param {Object} config.triggers - ボタンとモーダルの1:1対応を設定
 *                                   例: { '#btn_1': '#modal_a', '#btn_2': '#modal_b' }
 */
export function modalConfig(config) {
    if (!config || !config.triggers) {
        console.error('modalConfig: triggers is required');
        return;
    }

    // 各ボタンにイベントリスナーを設定
    Object.entries(config.triggers).forEach(([buttonSelector, modalSelector]) => {
        $(document).on('click', buttonSelector, function(e) {
            e.preventDefault();
            openModal(modalSelector);
        });
    });

    // モーダルの閉じるボタンにイベントリスナーを設定
    $(document).on('click', '.modal-close', function(e) {
        e.preventDefault();
        const $modal = $(this).closest('.modal-background');
        closeModal($modal);
    });

    // 背景をクリックしても閉じる
    $(document).on('click', '.modal-background', function(e) {
        // モーダルコンテンツエリアをクリックした場合は閉じない
        if ($(e.target).hasClass('modal-background')) {
            closeModal($(this));
        }
    });
}
