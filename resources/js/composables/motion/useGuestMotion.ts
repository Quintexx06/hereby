import { onBeforeUnmount, onMounted } from 'vue';
import type { Ref } from 'vue';

type Gsap = typeof import('@/lib/gsap');
type Setup = (kit: Gsap & { reduced: boolean }) => void;

const TRIGGERS = ['scroll', 'wheel', 'touchstart', 'pointerdown', 'keydown'];

/**
 * GSAP for guest pages without touching the first paint (rule 2): the
 * library is fetched on the guest's first scroll, touch or key (or at once
 * when the page opens already scrolled). Everything is scoped to `scope`
 * and reverted on unmount; reduced motion gets `reduced: true`.
 */
export function useGuestMotion(scope: Ref<HTMLElement | null>, setup: Setup) {
    let mm: { revert: () => void } | undefined;
    let cancelled = false;

    const start = async () => {
        TRIGGERS.forEach((name) => window.removeEventListener(name, start));
        const kit = await import('@/lib/gsap');
        if (cancelled || !scope.value) {
            return;
        }

        const media = kit.gsap.matchMedia(scope.value);
        media.add(
            {
                reduced: '(prefers-reduced-motion: reduce)',
                full: '(prefers-reduced-motion: no-preference)',
            },
            (context) =>
                setup({
                    ...kit,
                    reduced: Boolean(context.conditions?.reduced),
                }),
        );
        mm = media;
    };

    onMounted(() => {
        if (window.scrollY > 0) {
            void start();

            return;
        }
        TRIGGERS.forEach((name) =>
            window.addEventListener(name, start, { once: true, passive: true }),
        );
    });

    onBeforeUnmount(() => {
        cancelled = true;
        TRIGGERS.forEach((name) => window.removeEventListener(name, start));
        mm?.revert();
    });
}
