<script setup lang="ts">
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';

defineProps<{ couple: ShowcaseCoupleSite }>();
</script>

<!-- Garten: the photo under an arch, a branch on either side, centred names. -->
<template>
    <div class="sc-garten-hero">
        <svg
            v-for="side in ['left', 'right']"
            :key="side"
            class="sc-garten-twig"
            :data-side="side"
            viewBox="0 0 60 160"
            fill="none"
            aria-hidden="true"
        >
            <path d="M50 158C40 120 22 70 30 4" />
            <path d="M36 112c-14-4-22-14-24-26 12 2 22 12 24 26Z" />
            <path d="M33 74c12-6 20-16 20-28-12 4-20 14-20 28Z" />
            <path d="M31 40C20 34 15 24 16 12c10 6 15 16 15 28Z" />
        </svg>
        <p class="caption">{{ studio.site.welcomeKicker }}</p>
        <div class="sc-garten-arch">
            <ResponsivePhoto
                :photo="studio.site.photos.arch"
                sizes="(min-width: 1024px) 22rem, 60vw"
                class="size-full object-cover"
            />
        </div>
        <p class="showcase-names sc-garten-names">
            {{ couple.first }}
            <span class="text-brand">&amp;</span>
            {{ couple.second }}
        </p>
        <p class="text-sm font-medium text-muted-foreground sm:text-base">
            {{ couple.date }} · {{ studio.site.place }}
        </p>
    </div>
</template>
