<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';
import type { ThreeScene } from '@/lib/three/studio';
import { supportsWebGL } from '@/lib/three/support';

const TRIGGERS = [
    'scroll',
    'wheel',
    'touchstart',
    'pointerdown',
    'pointermove',
    'keydown',
];

/*
 * The 3D lights behind the Panorama hero. three.js is fetched only after the
 * guest's first interaction, so the first paint and the load budget never
 * carry it (rule 2); the canvas then fades in behind the names.
 */
const canvas = ref<HTMLCanvasElement | null>(null);
const ready = ref(false);
let scene: ThreeScene | null = null;
let cancelled = false;
let visibility: IntersectionObserver | undefined;

const onPointer = (event: PointerEvent) =>
    scene?.setPointer(
        event.clientX / window.innerWidth - 0.5,
        event.clientY / window.innerHeight - 0.5,
    );

async function start(): Promise<void> {
    TRIGGERS.forEach((name) => window.removeEventListener(name, start));
    const { createBokeh } = await import('@/lib/three/bokeh');
    if (cancelled || !canvas.value) {
        return;
    }
    scene = createBokeh(canvas.value, !prefersReducedMotion());
    ready.value = true;
    window.addEventListener('pointermove', onPointer, { passive: true });
    /* Pause the render loop once the hero has scrolled away. */
    visibility = new IntersectionObserver(([entry]) =>
        scene?.setActive(Boolean(entry?.isIntersecting)),
    );
    visibility.observe(canvas.value);
}

onMounted(() => {
    if (!supportsWebGL()) {
        return;
    }
    TRIGGERS.forEach((name) =>
        window.addEventListener(name, start, { once: true, passive: true }),
    );
});

onBeforeUnmount(() => {
    cancelled = true;
    TRIGGERS.forEach((name) => window.removeEventListener(name, start));
    window.removeEventListener('pointermove', onPointer);
    visibility?.disconnect();
    scene?.destroy();
});
</script>

<template>
    <canvas
        ref="canvas"
        class="panorama-bokeh"
        :class="ready ? 'opacity-100' : 'opacity-0'"
        aria-hidden="true"
    />
</template>
