import type { Ref } from 'vue';
import { onBeforeUnmount, onMounted, ref } from 'vue';

/**
 * through — 0 when the element's top enters the viewport bottom, 1 when its
 *           bottom leaves the top. For parallax and drawn lines.
 * pinned  — 0 when its top reaches the viewport top, 1 when its bottom reaches
 *           the viewport bottom. For sticky, scroll-jacked-free pinned tracks.
 * enter   — 0 as it enters at the bottom, 1 once its top reaches mid-screen.
 */
export type ScrollProgressMode = 'through' | 'pinned' | 'enter';

const clamp = (value: number) => Math.min(Math.max(value, 0), 1);

export function useScrollProgress(
    element: Ref<HTMLElement | null>,
    mode: ScrollProgressMode = 'through',
) {
    const progress = ref(0);
    let frame = 0;

    const measure = () => {
        frame = 0;
        const rect = element.value?.getBoundingClientRect();
        if (!rect) {
            return;
        }

        const viewport = window.innerHeight;
        if (mode === 'pinned') {
            progress.value = clamp(
                -rect.top / Math.max(rect.height - viewport, 1),
            );
        } else if (mode === 'enter') {
            progress.value = clamp((viewport - rect.top) / (viewport * 0.5));
        } else {
            progress.value = clamp(
                (viewport - rect.top) / (rect.height + viewport),
            );
        }
    };

    const schedule = () => {
        if (!frame) {
            frame = requestAnimationFrame(measure);
        }
    };

    onMounted(() => {
        measure();
        window.addEventListener('scroll', schedule, { passive: true });
        window.addEventListener('resize', schedule, { passive: true });
    });

    onBeforeUnmount(() => {
        cancelAnimationFrame(frame);
        window.removeEventListener('scroll', schedule);
        window.removeEventListener('resize', schedule);
    });

    return progress;
}
