import type { Ref } from 'vue';
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';

/** How long the track must be still before it counts as settled. */
const SETTLE_MS = 140;

/** An arrow step: long enough to read as travel, short enough to feel quick. */
const GLIDE_MS = 720;

/* Ease-in-out with a soft start and a long, calm landing. */
const glideCurve = (t: number): number =>
    t < 0.5 ? 4 * t * t * t : 1 - (-2 * t + 2) ** 3 / 2;

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
    /** True from the first scroll until the track rests; heavy work waits. */
    const moving = ref(false);
    let glide: number | null = null;
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
        moving.value = false;
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
        moving.value = true;
        if (glide !== null) {
            return;
        }
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

        if (glide !== null) {
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
        if (glide !== null) {
            cancelAnimationFrame(glide);
            glide = null;
        }
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

        glideTo(el, leftOf(el, slide));
    };

    /*
     * Arrow steps glide on our own curve instead of the browser's smooth
     * scroll: the same feel everywhere, and snapping is paused meanwhile so
     * it cannot tug the track mid-way. A new step starts from where it is.
     */
    const glideTo = (el: HTMLElement, target: number): void => {
        if (glide !== null) {
            cancelAnimationFrame(glide);
        }

        if (prefersReducedMotion()) {
            el.scrollTo({ left: target, behavior: 'instant' });
            index.value = nearest(el);
            rest();

            return;
        }

        const from = el.scrollLeft;
        const start = performance.now();
        el.dataset.gliding = 'true';
        moving.value = true;

        const frame = (now: number): void => {
            const progress = Math.min(1, (now - start) / GLIDE_MS);
            el.scrollLeft = from + (target - from) * glideCurve(progress);

            if (progress < 1) {
                glide = requestAnimationFrame(frame);

                return;
            }

            glide = null;
            delete el.dataset.gliding;
            index.value = nearest(el);
            rest();
        };

        glide = requestAnimationFrame(frame);
    };

    onMounted(() => {
        void nextTick(() => recentre(count + start));
        if (hasScrollEnd) {
            track.value?.addEventListener('scrollend', onScrollEnd);
        }
    });
    onBeforeUnmount(() => {
        clearTimeout(settle);
        if (glide !== null) {
            cancelAnimationFrame(glide);
        }
        track.value?.removeEventListener('scrollend', onScrollEnd);
    });

    return { track, slides, index, current, jumping, moving, onScroll, go };
}
