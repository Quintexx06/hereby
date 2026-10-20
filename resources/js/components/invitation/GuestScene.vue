<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';
import type { SceneFactory, ThreeScene } from '@/lib/three/studio';
import { supportsWebGL } from '@/lib/three/support';

/*
 * A decorative 3D layer on a guest page. three.js is fetched only after the
 * guest's first scroll, touch or key, and only once the canvas is near the
 * screen, so the first paint never carries it (rule 2). Data-saver
 * connections get none; reduced motion gets one still frame. The canvas
 * fades in when the first frame is drawn and pauses off screen.
 */
const props = defineProps<{ load: () => Promise<SceneFactory> }>();
const emit = defineEmits<{ ready: [] }>();

const TRIGGERS = ['scroll', 'wheel', 'touchstart', 'pointerdown', 'keydown'];

const canvas = ref<HTMLCanvasElement | null>(null);
const ready = ref(false);
let scene: ThreeScene | null = null;
let interacted = false;
let near = false;
let mounting = false;
let cancelled = false;
let observer: IntersectionObserver | undefined;

const onPointer = (event: PointerEvent) =>
    scene?.setPointer(
        event.clientX / window.innerWidth - 0.5,
        event.clientY / window.innerHeight - 0.5,
    );

const onScroll = () => {
    const rect = canvas.value?.getBoundingClientRect();
    if (rect) {
        scene?.setScroll(
            1 - (rect.top + rect.height) / (window.innerHeight + rect.height),
        );
    }
};

async function mount(): Promise<void> {
    mounting = true;
    const create = await props.load();
    if (cancelled || !canvas.value) {
        return;
    }
    scene = create(canvas.value, !prefersReducedMotion());
    scene.setActive(near);
    ready.value = true;
    emit('ready');
    window.addEventListener('pointermove', onPointer, { passive: true });
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

function update(): void {
    if (interacted && near && !scene && !mounting) {
        void mount();
    }
    scene?.setActive(near);
}

function interact(): void {
    TRIGGERS.forEach((name) => window.removeEventListener(name, interact));
    interacted = true;
    update();
}

onMounted(() => {
    const connection = (
        navigator as Navigator & { connection?: { saveData?: boolean } }
    ).connection;
    if (!canvas.value || !supportsWebGL() || connection?.saveData) {
        return;
    }
    observer = new IntersectionObserver(
        ([entry]) => {
            near = Boolean(entry?.isIntersecting);
            update();
        },
        { rootMargin: '200px 0px' },
    );
    observer.observe(canvas.value);
    if (window.scrollY > 0) {
        interact();
    } else {
        TRIGGERS.forEach((name) =>
            window.addEventListener(name, interact, {
                once: true,
                passive: true,
            }),
        );
    }
});

onBeforeUnmount(() => {
    cancelled = true;
    TRIGGERS.forEach((name) => window.removeEventListener(name, interact));
    window.removeEventListener('pointermove', onPointer);
    window.removeEventListener('scroll', onScroll);
    observer?.disconnect();
    scene?.destroy();
});
</script>

<template>
    <canvas
        ref="canvas"
        class="guest-scene"
        :data-ready="ready"
        aria-hidden="true"
    />
</template>
