import type { Ref } from 'vue';
import { onBeforeUnmount, onMounted, ref } from 'vue';
import type { VeilCurtain } from '@/lib/three/veil-curtain';
import { supportsWebGL } from '@/lib/three/support';
import { prefersReducedMotion } from '@/lib/motion';

/**
 * closed  — the CSS veil covers the photo (also the no-JS / slow-network look)
 * webgl   — the 3D veil has drawn its first frame and replaces the CSS veil
 * open    — the veil has parted (3D or CSS); hero copy is revealed
 */
export type CurtainState = 'closed' | 'webgl' | 'open';

const OPEN_MS = 2600;
const SKIP_MS = 700;
const WEBGL_DEADLINE_MS = 1600;
const REVEAL_AFTER_MS = 1100;
/** A breath with the veil closed, so the reveal reads as a reveal. */
const HOLD_MS = 450;

export function useVeilCurtain(
    canvas: Ref<HTMLCanvasElement | null>,
    photo: Ref<HTMLImageElement | null>,
) {
    const state = ref<CurtainState>('closed');
    /** Hero copy shows while the veil is still parting, not after. */
    const revealed = ref(false);
    /** True once the 3D veil has drawn; the CSS veil then stays hidden for good. */
    const usesWebgl = ref(false);
    let curtain: VeilCurtain | null = null;
    let visibility: IntersectionObserver | undefined;
    let deadline: ReturnType<typeof setTimeout> | undefined;
    let reveal: ReturnType<typeof setTimeout> | undefined;
    let cancelled = false;

    const photoReady = () =>
        photo.value?.decode().catch(() => undefined) ?? Promise.resolve();

    const openWithCss = () => {
        state.value = 'open';
        revealed.value = true;
    };

    const skip = () => {
        if (state.value === 'webgl') {
            revealed.value = true;
            void curtain?.open(SKIP_MS);
        } else if (state.value === 'closed') {
            openWithCss();
        }
    };

    const onPointer = (event: PointerEvent) =>
        curtain?.setPointer(event.clientX, event.clientY);

    onMounted(async () => {
        if (prefersReducedMotion() || !canvas.value || !supportsWebGL()) {
            await photoReady();
            openWithCss();

            return;
        }

        deadline = setTimeout(openWithCss, WEBGL_DEADLINE_MS);
        const [{ createVeilCurtain }] = await Promise.all([
            import('@/lib/three/veil-curtain'),
            photoReady(),
        ]);

        if (cancelled || state.value === 'open' || !canvas.value) {
            return;
        }

        clearTimeout(deadline);
        curtain = createVeilCurtain(canvas.value, () => {
            usesWebgl.value = true;
            state.value = 'webgl';
        });
        visibility = new IntersectionObserver(([entry]) =>
            curtain?.setActive(Boolean(entry?.isIntersecting)),
        );
        visibility.observe(canvas.value);
        window.addEventListener('pointermove', onPointer, { passive: true });

        await new Promise((resolve) => setTimeout(resolve, HOLD_MS));
        reveal = setTimeout(() => (revealed.value = true), REVEAL_AFTER_MS);
        await curtain?.open(OPEN_MS);
        state.value = 'open';
        revealed.value = true;
    });

    onBeforeUnmount(() => {
        cancelled = true;
        clearTimeout(deadline);
        clearTimeout(reveal);
        visibility?.disconnect();
        window.removeEventListener('pointermove', onPointer);
        curtain?.destroy();
    });

    return { state, revealed, usesWebgl, skip };
}
