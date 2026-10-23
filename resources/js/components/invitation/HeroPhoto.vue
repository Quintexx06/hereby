<script setup lang="ts">
import type { WeddingPhoto } from '@/types';

const { eager = false } = defineProps<{
    photo: WeddingPhoto;
    alt: string;
    /** The first screen's photo: load now, it is the page's largest paint. */
    eager?: boolean;
}>();
</script>

<!--
    One of the couple's photos: a web copy, sized so nothing jumps, cropped
    around their chosen spot. Photos further down wait behind the first.
-->
<template>
    <img
        :src="photo.url"
        :width="photo.width"
        :height="photo.height"
        :alt="alt"
        :loading="eager ? 'eager' : 'lazy'"
        :fetchpriority="eager ? 'high' : 'low'"
        decoding="async"
        class="object-cover"
        :style="{ objectPosition: `${photo.focus[0]}% ${photo.focus[1]}%` }"
    />
</template>
