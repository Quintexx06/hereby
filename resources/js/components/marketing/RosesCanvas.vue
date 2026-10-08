<script setup lang="ts">
import { ref } from 'vue';
import { useThreeScene } from '@/composables/motion/useThreeScene';

const stage = ref<HTMLElement | null>(null);
const canvas = ref<HTMLCanvasElement | null>(null);
const { isReady } = useThreeScene(canvas, stage, () =>
    import('@/lib/three/roses').then((m) => m.createRoses),
);
</script>

<!-- Decorative: three slowly turning roses, rendered with three.js on demand. -->
<template>
    <div ref="stage" class="roses-stage" aria-hidden="true">
        <canvas
            ref="canvas"
            class="h-full w-full transition-opacity duration-700 ease-out"
            :class="isReady ? 'opacity-100' : 'opacity-0'"
        />
    </div>
</template>
