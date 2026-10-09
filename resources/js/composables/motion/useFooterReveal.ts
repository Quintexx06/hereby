import type { Ref } from 'vue';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';

/**
 * The footer's entrance (GSAP, loaded only when the footer comes near):
 * the giant "hereby." lifts letter by letter and
 * the blush full stop drops in with a bounce. The letters are rendered
 * separately from the start, so nothing jumps at the end.
 */
export function useFooterReveal(root: Ref<HTMLElement | null>) {
    const isPending = ref(false);
    let observer: IntersectionObserver | undefined;
    let revert: (() => void) | undefined;

    const play = async () => {
        const { gsap } = await import('@/lib/gsap');
        const footer = root.value;
        if (!footer) {
            return;
        }

        // No masks: the letters are already separate elements, and the footer's
        // lower edge is the "floor" they rise out of. Nothing is swapped back
        // afterwards, so the last frame is exactly the resting layout.
        isPending.value = false;

        const timeline = gsap
            .timeline()
            .from(footer.querySelectorAll('[data-letter]'), {
                yPercent: 105,
                rotate: 6,
                duration: 1.1,
                ease: 'expo.out',
                stagger: 0.07,
            })
            .from(
                footer.querySelector('[data-stop]'),
                {
                    yPercent: -120,
                    opacity: 0,
                    duration: 0.9,
                    ease: 'bounce.out',
                },
                '-=0.55',
            );

        revert = () => timeline.kill();
    };

    onMounted(() => {
        if (!root.value || prefersReducedMotion()) {
            return;
        }

        isPending.value = true;
        observer = new IntersectionObserver(
            ([entry]) => {
                if (entry?.isIntersecting) {
                    observer?.disconnect();
                    void play();
                }
            },
            { threshold: 0.15 },
        );
        observer.observe(root.value);
    });

    onBeforeUnmount(() => {
        observer?.disconnect();
        revert?.();
    });

    return { isPending };
}
