<script setup lang="ts">
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';

defineProps<{ couple: ShowcaseCoupleSite }>();
</script>

<!-- Panorama: the names run past as a band; the photos go wall to wall. -->
<template>
    <div class="sc-panorama-hero">
        <div class="sc-panorama-band" aria-hidden="true">
            <span v-for="copy in 2" :key="copy" class="sc-panorama-run">
                <span v-for="n in 3" :key="n">
                    {{ couple.first }} <span class="showcase-amp">&amp;</span>
                    {{ couple.second }} ·
                </span>
            </span>
        </div>
        <div class="sc-panorama-strip">
            <ResponsivePhoto
                v-for="photo in studio.site.photos.strip"
                :key="photo.src"
                :photo="photo"
                sizes="(min-width: 1024px) 22rem, 45vw"
                class="sc-panorama-photo"
            />
        </div>
        <p class="sc-panorama-meta">
            <span>{{ couple.date }}</span>
            <span>{{ studio.site.place }}</span>
        </p>
    </div>
</template>
