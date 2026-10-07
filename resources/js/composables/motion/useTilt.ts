import type { Ref } from 'vue';
import { computed, ref } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';

/**
 * A card that leans towards the pointer like paper held in a hand.
 * Returns the transform and pointer handlers; flat under reduced motion.
 */
export function useTilt(element: Ref<HTMLElement | null>, maxDegrees = 7) {
    const x = ref(0);
    const y = ref(0);

    const onMove = (event: PointerEvent) => {
        const rect = element.value?.getBoundingClientRect();
        if (!rect || event.pointerType === 'touch' || prefersReducedMotion()) {
            return;
        }
        x.value = (event.clientX - rect.left) / rect.width - 0.5;
        y.value = (event.clientY - rect.top) / rect.height - 0.5;
    };

    const onLeave = () => {
        x.value = 0;
        y.value = 0;
    };

    const transform = computed(
        () =>
            `perspective(1200px) rotateY(${x.value * maxDegrees}deg) rotateX(${-y.value * maxDegrees}deg)`,
    );

    return { transform, onMove, onLeave };
}
