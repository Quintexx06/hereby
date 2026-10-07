import type { Ref } from 'vue';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';

/**
 * Counts from 0 to `target` (ease-out) the first time `trigger` turns true.
 * Shows the final value immediately under reduced motion or without JS.
 */
export function useCountUp(
    trigger: Ref<boolean>,
    target: number,
    durationMs = 1200,
) {
    const value = ref(target);
    let frame = 0;
    let started = false;

    const run = () => {
        started = true;
        const start = performance.now();
        const step = (now: number) => {
            const t = Math.min((now - start) / durationMs, 1);
            value.value = Math.round(target * (1 - Math.pow(1 - t, 4)));
            if (t < 1) {
                frame = requestAnimationFrame(step);
            }
        };
        frame = requestAnimationFrame(step);
    };

    onMounted(() => {
        if (prefersReducedMotion()) {
            return;
        }
        value.value = 0;
        if (trigger.value) {
            run();
        }
    });

    watch(trigger, (active) => {
        if (active && !started && !prefersReducedMotion()) {
            run();
        }
    });

    onBeforeUnmount(() => cancelAnimationFrame(frame));

    return value;
}
