import { onBeforeUnmount, onMounted, ref } from 'vue';

/**
 * Tracks the page section the reader is in (by element id) and whether the
 * page has scrolled past the hero. rAF-throttled, passive listeners.
 */
export function useActiveSection(ids: readonly string[], heroRatio = 0.85) {
    const active = ref<string | null>(null);
    const pastHero = ref(false);
    let frame = 0;

    const measure = () => {
        frame = 0;
        pastHero.value = window.scrollY > window.innerHeight * heroRatio;

        const probe = window.innerHeight * 0.4;
        active.value =
            [...ids].reverse().find((id) => {
                const rect = document
                    .getElementById(id)
                    ?.getBoundingClientRect();

                return rect ? rect.top <= probe && rect.bottom > 0 : false;
            }) ?? null;
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

    return { active, pastHero };
}
