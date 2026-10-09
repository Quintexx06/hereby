<script setup lang="ts">
import { ref } from 'vue';
import { useThreeScene } from '@/composables/motion/useThreeScene';
import type { SceneFactory } from '@/lib/three/studio';
import type { MastheadSceneName } from '@/types';

const props = defineProps<{ scene: MastheadSceneName }>();

/* Each page loads only its own scene, and only when the masthead is near. */
const loaders: Record<MastheadSceneName, () => Promise<SceneFactory>> = {
    roses: () => import('@/lib/three/roses').then((m) => m.createRoses),
    rings: () =>
        import('@/lib/three/wedding-rings').then((m) => m.createWeddingRings),
    petals: () => import('@/lib/three/petals').then((m) => m.createPetals),
    flutes: () => import('@/lib/three/flutes').then((m) => m.createFlutes),
    ribbon: () => import('@/lib/three/ribbon').then((m) => m.createRibbon),
};

const stage = ref<HTMLElement | null>(null);
const canvas = ref<HTMLCanvasElement | null>(null);
const { isReady } = useThreeScene(canvas, stage, loaders[props.scene]);
</script>

<!-- Decorative: the page's own little 3D scene in the masthead's margin. -->
<template>
    <div ref="stage" class="masthead-scene" aria-hidden="true">
        <canvas
            ref="canvas"
            class="h-full w-full transition-opacity duration-700 ease-out"
            :class="isReady ? 'opacity-100' : 'opacity-0'"
        />
    </div>
</template>
