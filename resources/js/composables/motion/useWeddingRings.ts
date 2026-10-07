import type { Ref } from 'vue';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import type { WeddingRings } from '@/lib/three/wedding-rings';
import { supportsWebGL } from '@/lib/three/support';
import { prefersReducedMotion } from '@/lib/motion';

/**
 * Mounts the 3D rings only when their section nears the viewport, follows
 * the pointer and the section's scroll progress, and pauses off screen.
 * Under reduced motion the rings render one still frame.
 */
export function useWeddingRings(
    canvas: Ref<HTMLCanvasElement | null>,
    section: Ref<HTMLElement | null>,
) {
    const isReady = ref(false);
    let rings: WeddingRings | null = null;
    let nearby: IntersectionObserver | undefined;
    let cancelled = false;

    const onPointer = (event: PointerEvent) =>
        rings?.setPointer(
            event.clientX / window.innerWidth - 0.5,
            event.clientY / window.innerHeight - 0.5,
        );

    const onScroll = () => {
        const rect = section.value?.getBoundingClientRect();
        if (rect) {
            rings?.setScroll(
                1 -
                    (rect.top + rect.height) /
                        (window.innerHeight + rect.height),
            );
        }
    };

    const mount = async () => {
        const { createWeddingRings } =
            await import('@/lib/three/wedding-rings');
        if (cancelled || !canvas.value) {
            return;
        }

        rings = createWeddingRings(canvas.value, !prefersReducedMotion());
        isReady.value = true;
        window.addEventListener('pointermove', onPointer, { passive: true });
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    };

    onMounted(() => {
        if (!section.value || !supportsWebGL()) {
            return;
        }

        nearby = new IntersectionObserver(
            ([entry]) => {
                const visible = Boolean(entry?.isIntersecting);
                if (visible && !rings) {
                    void mount();
                }
                rings?.setActive(visible);
            },
            { rootMargin: '300px 0px' },
        );
        nearby.observe(section.value);
    });

    onBeforeUnmount(() => {
        cancelled = true;
        nearby?.disconnect();
        window.removeEventListener('pointermove', onPointer);
        window.removeEventListener('scroll', onScroll);
        rings?.destroy();
    });

    return { isReady };
}
