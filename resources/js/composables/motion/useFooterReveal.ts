import type { Ref } from 'vue';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';

/**
 * The footer's entrance (GSAP, loaded only when the footer comes near):
 * the tagline rises word by word, the actions and links
 * follow one by one, then the giant "hereby." lifts letter by letter and
 * the blush full stop drops in with a bounce. The letters are rendered
 * separately from the start, so nothing jumps at the end.
 */
export function useFooterReveal(root: Ref<HTMLElement | null>) {
    const isPending = ref(false);
    let observer: IntersectionObserver | undefined;
    let revert: (() => void) | undefined;

    const play = async () => {
        const { gsap, SplitText } = await import('@/lib/gsap');
        const footer = root.value;
        if (!footer) {
            return;
        }

        // No masks: the letters are already separate elements, and the footer's
        // lower edge is the "floor" they rise out of. Nothing is swapped back
        // afterwards, so the last frame is exactly the resting layout.
        const tagline = SplitText.create(
            footer.querySelector('[data-tagline]'),
            { type: 'words' },
        );
        isPending.value = false;

        const timeline = gsap
            .timeline()
            .from(tagline.words, {
                y: 28,
                opacity: 0,
                filter: 'blur(6px)',
                duration: 0.9,
                ease: 'expo.out',
                stagger: 0.06,
            })
            .from(
                footer.querySelectorAll('[data-item]'),
                {
                    y: 18,
                    opacity: 0,
                    filter: 'blur(4px)',
                    duration: 0.7,
                    ease: 'expo.out',
                    stagger: 0.07,
                },
                '-=0.6',
            )
            .from(
                footer.querySelectorAll('[data-letter]'),
                {
                    yPercent: 105,
                    rotate: 6,
                    duration: 1.1,
                    ease: 'expo.out',
                    stagger: 0.07,
                },
                '-=0.5',
            )
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

        revert = () => {
            timeline.kill();
            tagline.revert();
        };
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
