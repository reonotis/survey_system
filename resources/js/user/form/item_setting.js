import $ from 'jquery';
import { modalConfig } from '../../common/modal.js';
import { initSortable } from '../../common/sortable.js';

// ドキュメント読み込み後にモーダル設定を初期化
$(document).ready(function() {
    modalConfig({
        triggers: {
            '#item_add_btn': '#item_add_modal' // item_add_btnをクリックしたら、#item_add_modalのモーダルを開く
        }
    });

    // Sortableを初期化
    initSortable('#form-items-sortable', {
        onEnd: function(evt) {
            // 並び替え完了後に実行されるメソッド
            handleSort(evt);
        }
    });

    /**
     * 並び替え完了時の処理
     * @param {Object} evt - Sortableのイベントオブジェクト
     */
    function handleSort(evt) {
        // 並び替え後の新しい順序を取得
        const items = Array.from(evt.to.children);
        const newOrder = items.map((item, index) => {
            const hiddenInput = item.querySelector('input[name^="item_id"]');
            if (hiddenInput) {
                // value属性からIDを取得し、整数に変換
                const itemId = parseInt(hiddenInput.value, 10);
                return isNaN(itemId) ? null : itemId;
            }
            return null;
        }).filter(id => id !== null);

        // 更新処理
        if (newOrder.length > 0) {
            updateItemOrder(newOrder);
        } else {
            console.error('項目IDが取得できませんでした');
        }
    }

    /**
     * 項目の順序を更新するメソッド
     * @param {Array} order - 項目IDの配列（新しい順序）
     */
    function updateItemOrder(order) {
        // data属性からURLを取得
        const orderForm = $('#update_item_order_form');
        if (orderForm.length === 0) {
            console.error('update_item_order_formが見つかりません');
            return;
        }
        const url = orderForm.data('item-order-url');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (!url) {
            console.error('URLが見つかりません');
            return;
        }

        // Ajaxで並び順を送信
        // Laravelが配列を正しく受け取れるように、order[]形式で送信
        const formData = new FormData();
        order.forEach((id, index) => {
            formData.append(`order[${index}]`, id);
        });
        formData.append('_token', csrfToken);

        $.ajax({
            url: url,
            method: 'POST',
            processData: false,
            contentType: false,
            data: formData,
            success: function(response) {
                console.log('順序が更新されました', response);
                // 必要に応じてUIの更新処理を追加
            },
            error: function(xhr, status, error) {
                console.error('順序の更新に失敗しました', error);
                if (xhr.responseJSON) {
                    console.error('エラー詳細:', xhr.responseJSON);
                }
                // エラーハンドリングを追加
            }
        });
    }


});
