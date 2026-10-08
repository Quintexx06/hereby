import type { Ref } from 'vue';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';
import type { SceneFactory, ThreeScene } from '@/lib/three/studio';
import { supportsWebGL } from '@/lib/three/support';

/**
 * Mounts a decorative 3D scene only when its section nears the viewport
 * (three.js is downloaded then, not before), follows the pointer and the
 * section's scroll progress, and pauses off screen. Under reduced motion
 * the scene renders one still frame.
 */
export function useThreeScene(
    canvas: Ref<HTMLCanvasElement | null>,
    section: Ref<HTMLElement | null>,
    load: () => Promise<SceneFactory>,
) {
    const isReady = ref(false);
    let instance: ThreeScene | null = null;
    let nearby: IntersectionObserver | undefined;
    let cancelled = false;

    const onPointer = (event: PointerEvent) =>
        instance?.setPointer(
            event.clientX / window.innerWidth - 0.5,
            event.clientY / window.innerHeight - 0.5,
        );

    const onScroll = () => {
        const rect = section.value?.getBoundingClientRect();
        if (rect) {
            instance?.setScroll(
                1 -
                    (rect.top + rect.height) /
                        (window.innerHeight + rect.height),
            );
        }
    };

    const mount = async () => {
        const create = await load();
        if (cancelled || !canvas.value) {
            return;
        }

        instance = create(canvas.value, !prefersReducedMotion());
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
                if (visible && !instance) {
                    void mount();
                }
                instance?.setActive(visible);
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
        instance?.destroy();
    });

    return { isReady };
}
