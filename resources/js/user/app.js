// import '../bootstrap';

import Alpine from 'alpinejs';
import '../../scss/user/app.scss';
import { initSessionMessage } from './session-message.js';

window.Alpine = Alpine;

Alpine.start();

// セッションメッセージの初期化
initSessionMessage();


document.addEventListener('DOMContentLoaded', () => {
    const GAP = 10;
    const EDGE = 8;

    const tooltips = document.querySelectorAll('.js-tooltip');

    const hideTooltip = (tooltip) => {
        tooltip.classList.remove('is-visible', 'is-top', 'is-bottom');
    };

    const positionTooltip = (tooltip) => {
        const trigger = tooltip.querySelector('.tooltip__trigger');
        const content = tooltip.querySelector('.tooltip__content');
        if (!trigger || !content) return;

        tooltip.classList.add('is-visible');
        content.style.left = '0px';
        content.style.top = '0px';

        const triggerRect = trigger.getBoundingClientRect();
        const contentRect = content.getBoundingClientRect();

        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;

        let placement = 'top';
        let top = triggerRect.top - contentRect.height - GAP;
        let left = triggerRect.left + (triggerRect.width / 2) - (contentRect.width / 2);

        if (top < EDGE) {
            placement = 'bottom';
            top = triggerRect.bottom + GAP;
        }

        if (left < EDGE) {
            left = EDGE;
        }

        if (left + contentRect.width > viewportWidth - EDGE) {
            left = viewportWidth - contentRect.width - EDGE;
        }

        if (placement === 'bottom' && top + contentRect.height > viewportHeight - EDGE) {
            placement = 'top';
            top = Math.max(EDGE, triggerRect.top - contentRect.height - GAP);
        }

        content.style.left = `${left}px`;
        content.style.top = `${top}px`;

        tooltip.classList.remove('is-top', 'is-bottom');
        tooltip.classList.add(placement === 'top' ? 'is-top' : 'is-bottom');

        const arrowLeft = Math.max(
            10,
            Math.min(
                contentRect.width - 20,
                triggerRect.left + (triggerRect.width / 2) - left - 5
            )
        );

        content.style.setProperty('--tooltip-arrow-left', `${arrowLeft}px`);
    };

    tooltips.forEach((tooltip) => {
        const trigger = tooltip.querySelector('.tooltip__trigger');
        if (!trigger) return;

        trigger.addEventListener('mouseenter', () => positionTooltip(tooltip));
        trigger.addEventListener('focus', () => positionTooltip(tooltip));

        trigger.addEventListener('mouseleave', () => hideTooltip(tooltip));
        trigger.addEventListener('blur', () => hideTooltip(tooltip));

        window.addEventListener('resize', () => {
            if (tooltip.classList.contains('is-visible')) {
                positionTooltip(tooltip);
            }
        });

        window.addEventListener('scroll', () => {
            if (tooltip.classList.contains('is-visible')) {
                positionTooltip(tooltip);
            }
        }, true);
    });
});
