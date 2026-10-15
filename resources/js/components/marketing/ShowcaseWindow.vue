<script setup lang="ts">
import ShowcaseBodyAtelier from '@/components/marketing/ShowcaseBodyAtelier.vue';
import ShowcaseBodyGarten from '@/components/marketing/ShowcaseBodyGarten.vue';
import ShowcaseBodyPanorama from '@/components/marketing/ShowcaseBodyPanorama.vue';
import ShowcaseHeroAtelier from '@/components/marketing/ShowcaseHeroAtelier.vue';
import ShowcaseHeroGarten from '@/components/marketing/ShowcaseHeroGarten.vue';
import ShowcaseHeroPanorama from '@/components/marketing/ShowcaseHeroPanorama.vue';
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';

defineProps<{
    /** Which of the three templates this window shows. */
    template: string;
    /** The slide in front: only its 3D layer renders. */
    active: boolean;
    /** Accent slug; only accent colours follow it, never the page ground. */
    accent: string;
    /** The couple as the visitor typed them (or the demo couple). */
    couple: ShowcaseCoupleSite;
}>();

const heroes: Record<string, unknown> = {
    atelier: ShowcaseHeroAtelier,
    garten: ShowcaseHeroGarten,
    panorama: ShowcaseHeroPanorama,
};
/* Same content below every hero, each told in its template's own way. */
const bodies: Record<string, unknown> = {
    atelier: ShowcaseBodyAtelier,
    garten: ShowcaseBodyGarten,
    panorama: ShowcaseBodyPanorama,
};
</script>

<!--
    One template of the demo couple's site in a browser window of fixed
    height; the site scrolls inside it, as it would in a real browser.
-->
<template>
    <figure class="showcase-window">
        <div class="showcase-bar" aria-hidden="true">
            <span class="flex gap-1.5">
                <span v-for="dot in 3" :key="dot" class="showcase-bar-dot" />
            </span>
            <span class="showcase-url">{{ couple.url }}</span>
        </div>

        <div
            class="showcase-site"
            data-theme="ivory"
            :data-accent="accent"
            :data-showcase="template"
            tabindex="0"
        >
            <div class="showcase-nav">
                <span class="showcase-monogram">{{ couple.monogram }}</span>
                <span class="hidden gap-6 text-sm md:flex">
                    <span v-for="link in studio.site.nav" :key="link">{{
                        link
                    }}</span>
                </span>
                <span class="showcase-action">{{ studio.site.action }}</span>
            </div>

            <component
                :is="heroes[template]"
                :couple="couple"
                :active="active"
            />
            <component :is="bodies[template]" />
        </div>
    </figure>
</template>
