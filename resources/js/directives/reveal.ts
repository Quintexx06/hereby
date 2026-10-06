import type { Directive } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';

/**
 * v-reveal — fade/rise an element in when it scrolls into view.
 * Pure CSS transition (see `reveal` utility); no animation library needed.
 * Usage: <div v-reveal class="reveal" /> or v-reveal="120" for a delay in ms.
 */
const observers = new WeakMap<HTMLElement, IntersectionObserver>();

export const reveal: Directive<HTMLElement, number | undefined> = {
    mounted(el, binding) {
        if (binding.value) {
            el.style.setProperty('--reveal-delay', `${binding.value}ms`);
        }

        if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
            el.dataset.revealed = '';

            return;
        }

        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry?.isIntersecting) {
                    el.dataset.revealed = '';
                    observer.disconnect();
                }
            },
            { rootMargin: '0px 0px -10% 0px' },
        );

        observer.observe(el);
        observers.set(el, observer);
    },
    unmounted(el) {
        observers.get(el)?.disconnect();
        observers.delete(el);
    },
};
