<script setup lang="ts">
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';

defineProps<{ couple: ShowcaseCoupleSite }>();
</script>

<!-- Atelier: the names set large over the couple's photo. -->
<template>
    <div class="showcase-hero">
        <ResponsivePhoto
            :photo="studio.site.photos.hero"
            sizes="(min-width: 1280px) 64rem, 90vw"
            class="showcase-hero-photo"
        />
        <div class="showcase-hero-text">
            <p class="showcase-names">
                {{ couple.first }}
                <span class="showcase-amp">&amp;</span>
                {{ couple.second }}
            </p>
            <p class="text-sm font-medium sm:text-base">
                {{ couple.date }} · {{ studio.site.place }}
            </p>
        </div>
    </div>
</template>
