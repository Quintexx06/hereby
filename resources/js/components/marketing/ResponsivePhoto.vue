<script setup lang="ts">
import type { LandingPhoto } from '@/content/landing-photos';

defineOptions({ inheritAttrs: false });

const { eager = false, sizes = '100vw' } = defineProps<{
    photo: LandingPhoto;
    sizes?: string;
    /** Above the fold: load now with high priority (the hero's LCP image). */
    eager?: boolean;
}>();

const image = defineModel<HTMLImageElement | null>('image', { default: null });
</script>

<!-- One photo, art-directed: a portrait crop on phones when there is one. -->
<template>
    <picture>
        <source
            v-if="photo.portrait"
            media="(max-width: 767px)"
            :srcset="photo.portrait"
        />
        <img
            :ref="(element) => (image = element as HTMLImageElement | null)"
            :src="photo.src"
            :srcset="photo.srcset"
            :sizes="photo.srcset ? sizes : undefined"
            :width="photo.width"
            :height="photo.height"
            :alt="photo.alt"
            :loading="eager ? 'eager' : 'lazy'"
            :fetchpriority="eager ? 'high' : 'auto'"
            decoding="async"
            v-bind="$attrs"
        />
    </picture>
</template>
