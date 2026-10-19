import { onBeforeUnmount, ref, watch } from 'vue';
import type { Ref } from 'vue';

export type SpotlightRect = {
    x: number;
    y: number;
    width: number;
    height: number;
};

/** Room around the highlighted element, so its edge never touches the ring. */
const PADDING = 8;

/** Whether the element is on screen: a closed drawer or `v-if` hides it. */
export function isShown(element: Element | null): element is HTMLElement {
    if (!(element instanceof HTMLElement)) {
        return false;
    }

    const box = element.getBoundingClientRect();
    const style = window.getComputedStyle(element);

    return (
        box.width > 0 &&
        box.height > 0 &&
        box.right > 0 &&
        style.visibility !== 'hidden'
    );
}

/**
 * Follows one element for a tour step: scrolls it to the middle of the
 * screen, then keeps its box (plus padding) current while the page scrolls
 * or resizes. `null` while there is nothing to highlight.
 */
export function useTourSpotlight(element: Ref<HTMLElement | null>) {
    const rect = ref<SpotlightRect | null>(null);
    const viewport = ref({ width: 0, height: 0 });
    let frame = 0;

    function measure(): void {
        frame = 0;
        viewport.value = {
            width: document.documentElement.clientWidth,
            height: window.innerHeight,
        };
        const box = element.value?.getBoundingClientRect();
        rect.value = box
            ? {
                  x: box.left - PADDING,
                  y: box.top - PADDING,
                  width: box.width + PADDING * 2,
                  height: box.height + PADDING * 2,
              }
            : null;
    }

    function schedule(): void {
        frame ||= window.requestAnimationFrame(measure);
    }

    function listen(on: boolean): void {
        const method = on ? 'addEventListener' : 'removeEventListener';
        window[method]('scroll', schedule, { capture: true, passive: true });
        window[method]('resize', schedule, { passive: true });
    }

    watch(
        element,
        (current, previous) => {
            if (current && !previous) {
                listen(true);
            } else if (!current && previous) {
                listen(false);
            }

            if (current) {
                const reduced = window.matchMedia(
                    '(prefers-reduced-motion: reduce)',
                ).matches;
                current.scrollIntoView({
                    block: 'center',
                    inline: 'nearest',
                    behavior: reduced ? 'auto' : 'smooth',
                });
            }

            measure();
        },
        { flush: 'post' },
    );

    onBeforeUnmount(() => {
        listen(false);
        window.cancelAnimationFrame(frame);
    });

    return { rect, viewport, refresh: measure };
}

type Size = { width: number; height: number };

const GAP = 14;
const MARGIN = 16;

const clamp = (value: number, min: number, max: number) =>
    Math.min(Math.max(value, min), Math.max(min, max));

/**
 * Where the step's card goes: beside a narrow element (the nav), else below
 * or above it, else pinned to the bottom. Always fully on screen.
 */
export function placeCard(
    rect: SpotlightRect | null,
    card: Size,
    view: Size,
): { top: number; left: number } {
    const centred = {
        top: (view.height - card.height) / 2,
        left: (view.width - card.width) / 2,
    };

    if (!rect) {
        return centred;
    }

    const left = clamp(rect.x, MARGIN, view.width - card.width - MARGIN);
    const top = clamp(rect.y, MARGIN, view.height - card.height - MARGIN);
    const right = rect.x + rect.width + GAP;
    const below = rect.y + rect.height + GAP;

    if (
        rect.width < view.width / 3 &&
        right + card.width <= view.width - MARGIN
    ) {
        return { top, left: right };
    }

    if (below + card.height <= view.height - MARGIN) {
        return { top: below, left };
    }

    if (rect.y - GAP - card.height >= MARGIN) {
        return { top: rect.y - GAP - card.height, left };
    }

    return { top: view.height - card.height - MARGIN, left: centred.left };
}

/** The dimmed page with a rounded hole over the spotlight, for clip-path. */
export function shadePath(rect: SpotlightRect | null, view: Size): string {
    const {
        x,
        y,
        width: w,
        height: h,
    } = rect ?? {
        x: view.width / 2,
        y: view.height / 2,
        width: 0,
        height: 0,
    };
    const r = Math.min(14, w / 2, h / 2);

    return [
        `M0 0H${view.width}V${view.height}H0Z`,
        `M${x + r} ${y}h${w - 2 * r}a${r} ${r} 0 0 1 ${r} ${r}`,
        `v${h - 2 * r}a${r} ${r} 0 0 1 ${-r} ${r}`,
        `h${-(w - 2 * r)}a${r} ${r} 0 0 1 ${-r} ${-r}`,
        `v${-(h - 2 * r)}a${r} ${r} 0 0 1 ${r} ${-r}Z`,
    ].join(' ');
}
