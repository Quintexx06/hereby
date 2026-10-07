<script setup lang="ts">
import { ref } from 'vue';
import { useWeddingRings } from '@/composables/motion/useWeddingRings';

const stage = ref<HTMLElement | null>(null);
const canvas = ref<HTMLCanvasElement | null>(null);
const { isReady } = useWeddingRings(canvas, stage);
</script>

<!-- Decorative: two interlinked bands, rendered with three.js on demand. -->
<template>
    <div ref="stage" class="rings-stage" aria-hidden="true">
        <div class="rings-shadow" />
        <canvas
            ref="canvas"
            class="relative h-full w-full transition-opacity duration-700 ease-out"
            :class="isReady ? 'opacity-100' : 'opacity-0'"
        />
    </div>
</template>
