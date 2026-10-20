<script setup lang="ts">
import GuestScene from '@/components/invitation/GuestScene.vue';

/* The 3D lights behind the Panorama hero, in a light tint of the accent. */
const bokeh = () =>
    import('@/lib/three/bokeh').then((scene) => scene.createBokeh);
</script>

<template>
    <GuestScene :load="bokeh" class="panorama-bokeh" />
</template>
