import { useResizeObserver } from '@vueuse/core';
import { onMounted, ref } from 'vue';
import type { Ref } from 'vue';

export type LineBox = {
    left: number;
    top: number;
    width: number;
    height: number;
};

/**
 * The rendered lines of a wrapping inline element, measured in px relative to
 * `container`. Use it to decorate text per line (strikes, highlights) without
 * the box stretching to the full column width when the text wraps.
 */
export function useLineBoxes(
    target: Ref<HTMLElement | null>,
    container: Ref<HTMLElement | null>,
): Ref<LineBox[]> {
    const lines = ref<LineBox[]>([]);

    const measure = (): void => {
        if (!target.value || !container.value) {
            return;
        }

        const base = container.value.getBoundingClientRect();
        const range = document.createRange();
        range.selectNodeContents(target.value);

        const boxes: LineBox[] = [];

        for (const rect of Array.from(range.getClientRects())) {
            if (rect.width === 0) {
                continue;
            }

            const top = rect.top - base.top;
            const left = rect.left - base.left;
            const previous = boxes.at(-1);

            // Fragments on the same line (words, text nodes) merge into one box.
            if (previous && Math.abs(previous.top - top) < rect.height / 2) {
                const right = Math.max(
                    previous.left + previous.width,
                    left + rect.width,
                );
                previous.left = Math.min(previous.left, left);
                previous.width = right - previous.left;
            } else {
                boxes.push({
                    left,
                    top,
                    width: rect.width,
                    height: rect.height,
                });
            }
        }

        lines.value = boxes;
    };

    useResizeObserver(container, measure);
    onMounted(() => {
        measure();
        void document.fonts?.ready.then(measure);
    });

    return lines;
}
