<script setup lang="ts">
import { computed } from 'vue';
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import { useTrans } from '@/composables/useTrans';
import type { Wedding } from '@/types';

/*
 * The couple's other photos (the cover is the hero's), under arches in
 * rows of three, the middle one tallest. Nothing shows while the couple
 * has only the cover.
 */
const props = defineProps<{ wedding: Wedding }>();
const { t } = useTrans();

const photos = computed(() => props.wedding.photos.slice(1));
const alt = computed(() =>
    t('invitation.photo_alt', { couple: props.wedding.coupleNames }),
);
</script>

<!-- A photo band between the day and the couple's words. -->
<template>
    <section
        v-if="photos.length"
        class="inv-gallery"
        data-variant="garten"
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
