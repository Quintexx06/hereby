<script setup lang="ts">
import { computed } from 'vue';
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import type { WeddingPhoto } from '@/types';

/* From: the first photo to show (the cover usually opens the page). */
const {
    photos,
    from = 1,
    count = 3,
} = defineProps<{
    photos: WeddingPhoto[];
    alt: string;
    from?: number;
    count?: number;
}>();

const shown = computed(() => photos.slice(from, from + count));
</script>

<!-- The couple's other photos; each design frames and lays them out its own way. -->
<template>
    <section v-if="shown.length" class="inv-photos" :data-count="shown.length">
        <figure
            v-for="photo in shown"
            :key="photo.url"
            class="inv-photos-frame"
        >
            <HeroPhoto :photo="photo" :alt="alt" class="inv-photos-image" />
        </figure>
    </section>
</template>
