import type { Directive } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';

/**
 * v-count-up: a number counts up from zero once, when it first appears
 * (dashboard and admin figures). 700ms, ease-out; non-numbers and reduced
 * motion show the value at once. Later changes simply show the new value.
 */
const DURATION = 700;

export const countUp: Directive<HTMLElement, number | string> = {
    mounted(el, binding) {
        const target = Number(binding.value);
        if (!Number.isFinite(target) || target <= 0 || prefersReducedMotion()) {
            return;
        }

        const start = performance.now();
        const tick = (now: number) => {
            const progress = Math.min((now - start) / DURATION, 1);
            const eased = 1 - (1 - progress) ** 3;
            el.textContent = String(Math.round(target * eased));
            if (progress < 1 && el.isConnected) {
                requestAnimationFrame(tick);
            }
        };
        el.textContent = '0';
        requestAnimationFrame(tick);
    },
    updated(el, binding) {
        if (binding.value !== binding.oldValue) {
            el.textContent = String(binding.value);
        }
    },
};
