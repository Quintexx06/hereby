<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';

/*
 * A soft light that follows the guest's pointer over the salon wall, as a
 * hand lamp would. Only for a fine pointer that hovers; it moves one fixed
 * layer (its own background position), never the page beneath.
 */
const spot = ref<HTMLElement | null>(null);
let frame = 0;
let x = 0;
let y = 0;

const paint = () => {
    frame = 0;
    spot.value?.style.setProperty('--spot-x', `${x}px`);
    spot.value?.style.setProperty('--spot-y', `${y}px`);
};

const onMove = (event: PointerEvent) => {
    x = event.clientX;
    y = event.clientY;
    frame ||= requestAnimationFrame(paint);
};

onMounted(() => {
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        spot.value?.setAttribute('data-on', '');
        window.addEventListener('pointermove', onMove, { passive: true });
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('pointermove', onMove);
    cancelAnimationFrame(frame);
});
</script>

<template>
    <div ref="spot" class="salon-spotlight" aria-hidden="true" />
</template>
