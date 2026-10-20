<script setup lang="ts">
import { computed } from 'vue';
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import { useTrans } from '@/composables/useTrans';
import type { Wedding } from '@/types';

/*
 * The couple's other photos (the cover is the hero's): Atelier lays them
 * out like a magazine spread, Garten under arches, Panorama as one wide
 * frame. Nothing shows while the couple has only the cover.
 */
const props = defineProps<{
    wedding: Wedding;
    variant: 'atelier' | 'garten' | 'panorama';
}>();
const { t } = useTrans();

const photos = computed(() =>
    props.variant === 'panorama'
        ? props.wedding.photos.slice(1, 2)
        : props.wedding.photos.slice(1, 4),
);
const alt = computed(() =>
    t('invitation.photo_alt', { couple: props.wedding.coupleNames }),
);
</script>

<!-- A photo band between the day and the couple's words. -->
<template>
    <section
        v-if="photos.length"
        class="inv-gallery"
        :data-variant="variant"
        :data-count="photos.length"
    >
        <figure
            v-for="photo in photos"
            :key="photo.url"
            class="inv-gallery-frame"
        >
            <HeroPhoto :photo="photo" :alt="alt" class="inv-gallery-photo" />
        </figure>
    </section>
</template>
