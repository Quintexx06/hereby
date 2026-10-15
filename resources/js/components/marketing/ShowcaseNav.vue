<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';

defineProps<{ template: string; couple: ShowcaseCoupleSite }>();

const links = studio.site.nav;
</script>

<!--
    Each template signs its site with its own header: Atelier as a magazine
    masthead, Garten around a monogram, Panorama as a floating night bar.
-->
<template>
    <header v-if="template === 'atelier'" class="showcase-nav sc-nav-atelier">
        <span class="sc-nav-names">
            {{ couple.first }} <span class="text-brand">&amp;</span>
            {{ couple.second }}
        </span>
        <nav class="sc-nav-links">
            <span v-for="link in links" :key="link">{{ link }}</span>
        </nav>
        <span class="sc-nav-text-action">
            {{ studio.site.action }} <ArrowRight class="size-4" />
        </span>
    </header>

    <header
        v-else-if="template === 'garten'"
        class="showcase-nav sc-nav-garten"
    >
        <nav class="sc-nav-links justify-self-start">
            <span v-for="link in links.slice(0, 2)" :key="link">{{
                link
            }}</span>
        </nav>
        <span class="sc-nav-seal">{{ couple.monogram }}</span>
        <span class="flex items-center gap-6 justify-self-end">
            <span class="sc-nav-links">{{ links[2] }}</span>
            <span class="sc-nav-outline">{{ studio.site.action }}</span>
        </span>
    </header>

    <header v-else class="showcase-nav sc-nav-panorama">
        <div class="sc-nav-bar">
            <span class="showcase-monogram text-porcelain-50">{{
                couple.monogram
            }}</span>
            <nav class="sc-nav-links">
                <span v-for="link in links" :key="link">{{ link }}</span>
            </nav>
            <span class="sc-nav-light">{{ studio.site.action }}</span>
        </div>
    </header>
</template>
