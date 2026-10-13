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
    /** Where an arrow is scrolling to; the next step starts there, not mid-way. */
    let aim: number | null = null;

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

    /** The track has come to rest: forget the aim, move back to the middle. */
    const rest = (): void => {
        aim = null;
        if (index.value < count || index.value >= count * 2) {
            recentre(count + current.value);
        }
    };

    /*
     * `scrollend` knows when a smooth scroll has truly finished, however
     * slow the frames; a quiet spell of SETTLE_MS is the fallback where the
     * browser lacks it (older Safari).
     */
    const hasScrollEnd =
        typeof window !== 'undefined' && 'onscrollend' in window;

    const onScroll = (): void => {
        const el = track.value;
        if (!el) {
            return;
        }

        index.value = nearest(el);
        if (!hasScrollEnd) {
            clearTimeout(settle);
            settle = setTimeout(rest, SETTLE_MS);
        }
    };

    const onScrollEnd = (): void => {
        const el = track.value;
        if (!el) {
            return;
        }

        /* An end that fires on the way to an arrow's slide is not the rest. */
        const target = aim === null ? null : slides.value[aim];
        if (target && Math.abs(el.scrollLeft - leftOf(el, target)) > 2) {
            return;
        }

        index.value = nearest(el);
        rest();
    };

    const go = (step: number): void => {
        const el = track.value;
        if (!el) {
            return;
        }

        /*
         * Step from the slide already aimed at (a click mid-scroll must not
         * be lost), moved to the middle copy first when it lies outside it,
         * so a jump back still pending cannot cancel this scroll.
         */
        clearTimeout(settle);
        let from = aim ?? index.value;
        if (from < count || from >= count * 2) {
            from = count + (from % count);
            recentre(from);
        }

        aim = from + step;
        const slide = slides.value[aim];
        if (!slide) {
            return;
        }

        el.scrollTo({
            left: leftOf(el, slide),
            behavior: prefersReducedMotion() ? 'auto' : 'smooth',
        });
    };

    onMounted(() => {
        void nextTick(() => recentre(count + start));
        if (hasScrollEnd) {
            track.value?.addEventListener('scrollend', onScrollEnd);
        }
    });
    onBeforeUnmount(() => {
        clearTimeout(settle);
        track.value?.removeEventListener('scrollend', onScrollEnd);
    });

    return { track, slides, index, current, jumping, onScroll, go };
}
