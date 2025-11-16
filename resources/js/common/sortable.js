import Sortable from 'sortablejs';

/**
 * Sortableを初期化する共通関数
 * @param {HTMLElement|string} element - Sortableを適用する要素またはセレクター
 * @param {Object} options - Sortableのオプション（デフォルト値が適用される）
 * @param {string} options.handle - ドラッグハンドルのセレクター（デフォルト: null）
 * @param {number} options.animation - アニメーション時間（デフォルト: 150）
 * @param {string} options.ghostClass - ゴーストクラス名（デフォルト: 'drag-ghost'）
 * @param {string} options.chosenClass - 選択時のクラス名（デフォルト: 'drag-chosen'）
 * @param {string} options.dragClass - ドラッグ中のクラス名（デフォルト: 'drag-dragging'）
 * @param {Function} options.onEnd - 並び替え完了時のコールバック関数
 * @returns {Sortable|null} - 初期化されたSortableインスタンス、またはnull（要素が見つからない場合）
 */
export function initSortable(element, options = {}) {
    // 要素を取得
    const el = typeof element === 'string' ? document.querySelector(element) : element;

    if (!el) {
        console.warn('Sortable: 要素が見つかりません', element);
        return null;
    }

    // デフォルトオプション
    const defaultOptions = {
        animation: 150,
        ghostClass: 'drag-ghost',
        chosenClass: 'drag-chosen',
        dragClass: 'drag-dragging',
        handle: '.sort-handle',
    };

    // オプションをマージ
    const sortableOptions = {
        ...defaultOptions,
        ...options,
    };

    // Sortableを初期化
    return new Sortable(el, sortableOptions);
}
