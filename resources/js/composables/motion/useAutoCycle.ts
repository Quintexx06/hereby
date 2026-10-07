import { onBeforeUnmount, onMounted, ref } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';

/**
 * Steps an index through `count` items every `intervalMs`. Never autoplays
 * under reduced motion; `pause()`/`resume()` for hover and focus, and
 * `select()` hands control to the visitor for good.
 */
export function useAutoCycle(count: number, intervalMs = 3800) {
    const index = ref(0);
    const isPaused = ref(false);
    let timer: ReturnType<typeof setInterval> | undefined;

    const stop = () => {
        clearInterval(timer);
        timer = undefined;
    };

    const start = () => {
        if (timer || prefersReducedMotion() || count < 2) {
            return;
        }

        timer = setInterval(() => {
            if (!isPaused.value && !document.hidden) {
                index.value = (index.value + 1) % count;
            }
        }, intervalMs);
    };

    const select = (next: number) => {
        index.value = next;
        stop();
    };

    onMounted(start);
    onBeforeUnmount(stop);

    return {
        index,
        select,
        pause: () => (isPaused.value = true),
        resume: () => (isPaused.value = false),
    };
}
