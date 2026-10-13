<script setup lang="ts">
import { ref, toRef } from 'vue';
import { useThreeScene } from '@/composables/motion/useThreeScene';
import type { SceneFactory } from '@/lib/three/studio';

export type ShowcaseSceneName = 'sparkler' | 'petals' | 'bokeh';

const props = defineProps<{
    scene: ShowcaseSceneName;
    /** Only the slide in front renders; the others wait. */
    active: boolean;
}>();

/* Each design loads only its own scene, and only once its slide is in front. */
const loaders: Record<ShowcaseSceneName, () => Promise<SceneFactory>> = {
    sparkler: () =>
        import('@/lib/three/sparkler').then((m) => m.createSparkler),
    /* A wide window: more petals, smaller, spread across it. */
    petals: () =>
        import('@/lib/three/petals').then(
            (m): SceneFactory =>
                (canvas, animate) =>
                    m.createPetals(canvas, animate, {
                        count: 28,
                        size: 0.42,
                        spread: 1.8,
                    }),
        ),
    bokeh: () => import('@/lib/three/bokeh').then((m) => m.createBokeh),
};

const stage = ref<HTMLElement | null>(null);
const canvas = ref<HTMLCanvasElement | null>(null);
const { isReady } = useThreeScene(
    canvas,
    stage,
    loaders[props.scene],
    toRef(props, 'active'),
);
</script>

<!-- Decorative: a design's own 3D layer, lazy and paused when not in front. -->
<template>
    <div ref="stage" class="sc-scene" aria-hidden="true">
        <canvas
            ref="canvas"
            class="size-full transition-opacity duration-700 ease-out"
            :class="isReady ? 'opacity-100' : 'opacity-0'"
        />
    </div>
</template>
