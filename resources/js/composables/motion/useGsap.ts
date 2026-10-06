import { onBeforeUnmount, onMounted } from 'vue';
import type { Ref } from 'vue';
import { gsap } from '@/lib/gsap';

type Setup = (context: { reduced: boolean }) => void;

/**
 * Runs GSAP code scoped to a template ref and reverts everything on unmount
 * (safe with Inertia page swaps). Selector strings inside `setup` resolve
 * within `scope` only. Reduced-motion users get `reduced: true` — show the
 * final state instead of animating.
 */
export function useGsap(scope: Ref<HTMLElement | null>, setup: Setup): void {
    let mm: gsap.MatchMedia | undefined;

    onMounted(() => {
        if (!scope.value) {
            return;
        }

        mm = gsap.matchMedia(scope.value);
        mm.add(
            {
                reduced: '(prefers-reduced-motion: reduce)',
                full: '(prefers-reduced-motion: no-preference)',
            },
            (ctx) => setup({ reduced: Boolean(ctx.conditions?.reduced) }),
        );
    });

    onBeforeUnmount(() => mm?.revert());
}
