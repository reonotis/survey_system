import $ from 'jquery';

/**
 * 汎用トグルハンドラ（表示/非表示の制御をまとめたユーティリティ）
 *
 * ポイント（新人向け）:
 * - 画面上の「ある要素（target）」を、入力値（ラジオ/チェックボックス/セレクト/テキストなど）の組み合わせ条件で表示/非表示にします。
 * - 条件は複数指定でき、全て満たす（AND）か、どれか1つでも満たす（OR）を選べます。
 * - 監視はchange/inputイベントで行い、値が変わる度に自動再評価します。
 *
 * 代表的な使い方:
 * setupConditionalToggle('#target', [
 *   { selector: 'input[name="A"]', anyOf: ['1', '2'] }, // Aが1か2
 *   { selector: 'select[name="B"]', anyOf: ['3'] }      // かつ Bが3
 * ], { logic: 'AND', initialEval: true });
 *
 * noneOfを使うと「その値でないとき」を条件にできます。
 * 例）{ selector: 'input[name="C"]', noneOf: ['0'] } // Cが0『以外』
 */

/**
 * @typedef {Object} Condition
 * @property {string} selector 監視対象のセレクタ（例: 'input[name="A"]'）
 * @property {string[]} [anyOf] いずれか一致でOK（OR）な値の集合（文字列配列）
 * @property {string[]} [noneOf] どの値にも一致してはいけない集合（文字列配列）
 */

/**
 * @typedef {Object} Options
 * @property {'AND'|'OR'} [logic='AND'] 条件の結合方法（AND/OR）
 * @property {boolean} [initialEval=true] 初期表示時に即評価するか
 */

/**
 * 現在値を取得（型に応じて）
 * @param {string} selector
 * @returns {string[]}
 */
function getCurrentValues(selector) {
    const $els = $(selector);
    if ($els.length === 0) return [];

    // radio グループ（同nameの中で選択された1件を取得）
    if ($els.first().is(':radio')) {
        const $checked = $els.filter(':checked');
        return $checked.length ? [$checked.val()] : [];
    }

    // checkbox（複数選択の可能性があるため配列）
    if ($els.first().is(':checkbox')) {
        const vals = $els.filter(':checked').map(function() { return $(this).val(); }).get();
        return vals;
    }

    // select
    if ($els.first().is('select')) {
        const val = $els.val();
        if (Array.isArray(val)) return val.map(String);
        return (val !== undefined && val !== null) ? [String(val)] : [];
    }

    // input[text|hidden] 等（最初の1要素の値を使用）
    const val = $els.first().val();
    return (val !== undefined && val !== null) ? [String(val)] : [];
}

/**
 * 条件評価
 * @param {{selector: string, anyOf?: string[], noneOf?: string[] }[]} conditions
 * @param {'AND'|'OR'} logic
 * @returns {boolean}
 */
function evaluateConditions(conditions, logic) {
    if (!Array.isArray(conditions) || conditions.length === 0) return true;

    const results = conditions.map((c) => {
        const values = getCurrentValues(c.selector);
        let ok = true;
        // anyOf: valuesの中に、指定した候補が1つでも含まれていればOK
        if (c.anyOf && c.anyOf.length > 0) {
            ok = values.some(v => c.anyOf.includes(String(v)));
        }
        // noneOf: valuesが指定した候補を1つも含まないこと
        if (ok && c.noneOf && c.noneOf.length > 0) {
            ok = values.every(v => !c.noneOf.includes(String(v)));
        }
        return ok;
    });

    if (logic === 'OR') {
        return results.some(Boolean);
    }
    // default AND
    return results.every(Boolean);
}

/**
 * 条件に応じて要素の表示/非表示を制御
 * @param {string} targetSelector 表示/非表示の対象
 * @param {{selector: string, anyOf?: string[], noneOf?: string[]}[]} conditions 条件配列
 * @param {{logic?: 'AND'|'OR', initialEval?: boolean}} options
 */
export function setupConditionalToggle(targetSelector, conditions, options = {}) {
    const logic = options.logic === 'OR' ? 'OR' : 'AND';
    const initialEval = options.initialEval !== false; // 既定 true
    const debug = options.debug === true;

    const $target = $(targetSelector);
    if ($target.length === 0) return;

    const uniqueSelectors = Array.from(new Set(conditions.map(c => c.selector)));

    // 値が変わる度に呼び出す「評価 → 表示/非表示」関数
    const reevaluate = () => {
        const show = evaluateConditions(conditions, logic);
        if (debug) {
            try {
                const snapshot = conditions.map((c) => ({
                    selector: c.selector,
                    values: (function(sel){ return $(sel).map(function(){ return $(this).is(':radio')? ($(sel+':checked').val() || '') : $(this).val(); }).get(); })(c.selector),
                    anyOf: c.anyOf,
                    noneOf: c.noneOf,
                }));
                // eslint-disable-next-line no-console
                console.log('[toggleHandler] reevaluate', { targetSelector, logic, show, snapshot });
            } catch (e) {
                // eslint-disable-next-line no-console
                console.warn('[toggleHandler] debug logging failed', e);
            }
        }
        if (show) {
            $target.slideDown(200);
        } else {
            $target.slideUp(200);
        }
    };

    // 入力値の変化を監視（ラジオ/チェック/テキスト/セレクト全般をカバー）
    uniqueSelectors.forEach((sel) => {
        $(document).on('change input', sel, reevaluate);
    });

    // 画面初期表示時に評価して、正しい表示状態にしておく
    if (initialEval) {
        reevaluate();
    }

    return { reevaluate };
}


