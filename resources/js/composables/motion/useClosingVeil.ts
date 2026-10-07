import type { Ref } from 'vue';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { useScrollProgress } from '@/composables/motion/useScrollProgress';
import type { VeilCurtain } from '@/lib/three/veil-curtain';
import { supportsWebGL } from '@/lib/three/support';
import { prefersReducedMotion } from '@/lib/motion';

/**
 * The last scene: the veil from the hero drifts back in as the page ends,
 * framing the couple. Scroll drives it; mounted only near the section.
 */
export function useClosingVeil(
    canvas: Ref<HTMLCanvasElement | null>,
    section: Ref<HTMLElement | null>,
) {
    const isLive = ref(false);
    const progress = useScrollProgress(section, 'through');
    let curtain: VeilCurtain | null = null;
    let nearby: IntersectionObserver | undefined;
    let cancelled = false;

    /** Gathered (1) as the section enters, about two-thirds drawn at its centre. */
    const openFor = (value: number) => 1 - Math.min(value / 0.55, 1) * 0.42;

    const mount = async () => {
        const { createVeilCurtain } = await import('@/lib/three/veil-curtain');
        if (cancelled || !canvas.value) {
            return;
        }
        curtain = createVeilCurtain(canvas.value, () => (isLive.value = true));
        curtain.setOpen(openFor(progress.value));
    };

    watch(progress, (value) => curtain?.setOpen(openFor(value)));

    onMounted(() => {
        if (prefersReducedMotion() || !section.value || !supportsWebGL()) {
            return;
        }
        nearby = new IntersectionObserver(
            ([entry]) => {
                const visible = Boolean(entry?.isIntersecting);
                if (visible && !curtain) {
                    void mount();
                }
                curtain?.setActive(visible);
            },
            { rootMargin: '400px 0px' },
        );
        nearby.observe(section.value);
    });

    onBeforeUnmount(() => {
        cancelled = true;
        nearby?.disconnect();
        curtain?.destroy();
    });

    return { isLive };
}
