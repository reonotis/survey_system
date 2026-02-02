// import '../bootstrap';

import Alpine from 'alpinejs';
import '../../scss/form/app.scss';

window.Alpine = Alpine;

Alpine.start();

/**
 * チェックボックス項目ごとに最大選択数（max_count）を適用する。
 * 各項目は独立しており、他の項目のチェックボックスには影響しない。
 */
function initCheckboxMaxCount() {
    document.addEventListener('change', (e) => {
        const checkbox = e.target;
        if (checkbox.type !== 'checkbox' || !checkbox.classList.contains('form-check-input')) {
            return;
        }
        const name = checkbox.getAttribute('name');
        const match = name && name.match(/^checkbox_(\d+)\[\]$/);
        if (!match) return;

        const formItemId = match[1];
        const group = document.querySelector(`.checkbox-group[data-form-item-id="${formItemId}"]`);
        if (!group) return;

        const maxCount = parseInt(group.getAttribute('data-max-count'), 10);
        if (isNaN(maxCount) || maxCount <= 0) return;

        const checkboxesInGroup = group.querySelectorAll(`input[name="checkbox_${formItemId}[]"]`);
        const checkedCount = Array.from(checkboxesInGroup).filter((cb) => cb.checked).length;

        checkboxesInGroup.forEach((cb) => {
            if (cb.hasAttribute('data-initially-disabled')) return;

            if (checkedCount >= maxCount) {
                if (!cb.checked) {
                    cb.disabled = true;
                    cb.setAttribute('data-disabled-by-max', '1');
                }
            } else {
                if (cb.hasAttribute('data-disabled-by-max')) {
                    cb.disabled = false;
                    cb.removeAttribute('data-disabled-by-max');
                }
            }
        });
    });

    // 初期表示時（ページ読み込み時）に、既に max_count に達している項目の未選択を無効化
    document.querySelectorAll('.checkbox-group[data-max-count]').forEach((group) => {
        const formItemId = group.getAttribute('data-form-item-id');
        const maxCount = parseInt(group.getAttribute('data-max-count'), 10);
        if (isNaN(maxCount) || maxCount <= 0) return;

        const checkboxesInGroup = group.querySelectorAll(`input[name="checkbox_${formItemId}[]"]`);
        const checkedCount = Array.from(checkboxesInGroup).filter((cb) => cb.checked).length;

        if (checkedCount >= maxCount) {
            checkboxesInGroup.forEach((cb) => {
                if (!cb.hasAttribute('data-initially-disabled') && !cb.checked) {
                    cb.disabled = true;
                    cb.setAttribute('data-disabled-by-max', '1');
                }
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', initCheckboxMaxCount);
