<script setup lang="ts">
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import { landingPhotos } from '@/content/landing-photos';

const photos = Object.values(landingPhotos).slice(0, 6);
</script>

<!-- The gallery as it fills on the day: the guests' photos in a grid. -->
<template>
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
        <ResponsivePhoto
            v-for="photo in photos"
            :key="photo.src"
            :photo="photo"
            sizes="20rem"
            class="aspect-square w-full rounded-lg object-cover"
        />
    </div>
</template>
