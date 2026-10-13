import type { Ref } from 'vue';
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';

/** How long the track must be still before it counts as settled. */
const SETTLE_MS = 140;

/**
 * An endless scroll-snap track. The items are rendered three times; the
 * visitor always rests in the middle copy. Whenever the track settles on a
 * slide in the first or last copy, it jumps by one copy's width to the same
 * slide in the middle, so swiping never reaches an end. `jumping` is true
 * for that one frame, so slide transitions can be switched off for it.
 */
export function useLoopingTrack(count: number, start = 0) {
    const track = ref<HTMLElement | null>(null);
    const slides = ref<HTMLElement[]>([]) as Ref<HTMLElement[]>;
    const index = ref(count + start);
    const jumping = ref(false);
    const current = computed(() => index.value % count);
    let settle: ReturnType<typeof setTimeout> | undefined;

    /** The slide nearest the middle of the track is the one in view. */
    const nearest = (el: HTMLElement): number => {
        const middle = el.scrollLeft + el.clientWidth / 2;
        const distances = slides.value.map((slide) =>
            Math.abs(slide.offsetLeft + slide.offsetWidth / 2 - middle),
        );

        return distances.indexOf(Math.min(...distances));
    };

    const leftOf = (el: HTMLElement, slide: HTMLElement): number =>
        slide.offsetLeft - (el.clientWidth - slide.offsetWidth) / 2;

    /** Teleport to the same slide in the middle copy, invisibly. */
    const recentre = (target: number): void => {
        const el = track.value;
        const slide = slides.value[target];
        if (!el || !slide) {
            return;
        }

        jumping.value = true;
        index.value = target;
        el.scrollTo({ left: leftOf(el, slide), behavior: 'instant' });
        requestAnimationFrame(() =>
            requestAnimationFrame(() => (jumping.value = false)),
        );
    };

    const onScroll = (): void => {
        const el = track.value;
        if (!el) {
            return;
        }

        index.value = nearest(el);
        clearTimeout(settle);
        settle = setTimeout(() => {
            if (index.value < count || index.value >= count * 2) {
                recentre(count + current.value);
            }
        }, SETTLE_MS);
    };

    const go = (step: number): void => {
        const el = track.value;
        const target = Math.min(Math.max(index.value + step, 0), count * 3 - 1);
        const slide = slides.value[target];
        if (!el || !slide) {
            return;
        }

        el.scrollTo({
            left: leftOf(el, slide),
            behavior: prefersReducedMotion() ? 'auto' : 'smooth',
        });
    };

    onMounted(() => nextTick(() => recentre(count + start)));
    onBeforeUnmount(() => clearTimeout(settle));

    return { track, slides, index, current, jumping, onScroll, go };
}
