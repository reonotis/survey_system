// セッションメッセージのスライドイン処理（画面描画後に実行）
function slideInMessages() {
    const messages = document.querySelectorAll('.session-message');

    messages.forEach((message) => {
        message.style.right = '0';
    });
}

// セッションメッセージの閉じるボタン処理
export function initSessionMessage() {
    setTimeout(slideInMessages, 100);

    document.addEventListener('click', function(e) {
        if (e.target.closest('.session-close')) {
            const message = e.target.closest('.session-message');
            if (message) {
                // 初期状態を保存
                const initialHeight = message.offsetHeight;
                const initialMarginTop = window.getComputedStyle(message).marginTop;
                const initialMarginBottom = window.getComputedStyle(message).marginBottom;
                const initialPaddingTop = window.getComputedStyle(message).paddingTop;
                const initialPaddingBottom = window.getComputedStyle(message).paddingBottom;

                // ステップ1: 右にスライド（0.1秒）
                message.style.transition = 'transform 0.1s ease-out';
                message.style.transform = 'translateX(150%)';

                // ステップ2: 右スライド完了後に上に折りたたむ（0.1秒）
                setTimeout(() => {
                    message.style.transition = 'max-height 0.1s ease-out, margin 0.1s ease-out, padding 0.1s ease-out, opacity 0.1s ease-out';
                    message.style.maxHeight = initialHeight + 'px';
                    message.style.marginTop = initialMarginTop;
                    message.style.marginBottom = initialMarginBottom;
                    message.style.paddingTop = initialPaddingTop;
                    message.style.paddingBottom = initialPaddingBottom;
                    message.style.opacity = '1';

                    // 強制的にリフロー
                    message.offsetHeight;

                    // 折りたたみ開始
                    message.style.maxHeight = '0';
                    message.style.marginTop = '0';
                    message.style.marginBottom = '0';
                    message.style.paddingTop = '0';
                    message.style.paddingBottom = '0';
                    message.style.opacity = '0';

                    // ステップ3: 折りたたみ完了後に削除
                    setTimeout(() => {
                        message.remove();
                    }, 100);
                }, 100);
            }
        }
    });
}

