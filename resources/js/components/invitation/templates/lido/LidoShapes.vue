<script setup lang="ts">
import { computed } from 'vue';
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import type { WeddingPhoto } from '@/types';

const props = defineProps<{ photos: WeddingPhoto[]; alt: string }>();

/* The cut each photo gets, in turn: a sun, a pebble, a wave, a capsule, a tile. */
const SHAPES = ['sun', 'pebble', 'wave', 'capsule', 'tile'] as const;
const shown = computed(() =>
    props.photos.slice(1, 8).map((photo, index) => ({
        photo,
        shape: SHAPES[index % SHAPES.length],
    })),
);
</script>

<!--
    The couple's other photos cut out like a summer collage, never framed:
    a sun, a pebble, a wave-edged print, a capsule, a tilted tile, each
    bobbing a little as if floating on the lake.
-->
<template>
    <section v-if="shown.length" class="lido-shapes" :data-count="shown.length">
        <figure
            v-for="(each, index) in shown"
            :key="each.photo.url"
            class="lido-shape"
            :data-shape="each.shape"
            :style="{ '--i': index }"
        >
            <HeroPhoto
                :photo="each.photo"
                :alt="alt"
                class="lido-shape-photo"
            />
        </figure>
    </section>
</template>
