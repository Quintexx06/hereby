<script setup lang="ts">
import ShowcaseBodyAtelier from '@/components/marketing/ShowcaseBodyAtelier.vue';
import ShowcaseBodyGarten from '@/components/marketing/ShowcaseBodyGarten.vue';
import ShowcaseBodyPanorama from '@/components/marketing/ShowcaseBodyPanorama.vue';
import ShowcaseFooter from '@/components/marketing/ShowcaseFooter.vue';
import ShowcaseNav from '@/components/marketing/ShowcaseNav.vue';
import ShowcaseHeroAtelier from '@/components/marketing/ShowcaseHeroAtelier.vue';
import ShowcaseHeroGarten from '@/components/marketing/ShowcaseHeroGarten.vue';
import ShowcaseHeroPanorama from '@/components/marketing/ShowcaseHeroPanorama.vue';
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';

defineProps<{
    template: string;
    active: boolean;
    accent: string;
    couple: ShowcaseCoupleSite;
    /** In the carousel the site only shows its top; it scrolls in the popup. */
    preview: boolean;
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

<!-- The demo couple's whole site in one template, down to its footer. -->
<template>
    <div
        class="showcase-site"
        data-theme="ivory"
        :data-accent="accent"
        :data-showcase="template"
        :data-preview="preview"
        :tabindex="preview ? undefined : 0"
    >
        <ShowcaseNav :template="template" :couple="couple" />

        <component :is="heroes[template]" :couple="couple" :active="active" />
        <component :is="bodies[template]" />
        <ShowcaseFooter :couple="couple" />
    </div>
</template>
