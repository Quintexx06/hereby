<script setup lang="ts">
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import ShowcaseDetails from '@/components/marketing/ShowcaseDetails.vue';
import { studio } from '@/content/landing';

defineProps<{
    /** Accent slug; only accent colours follow it, never the page ground. */
    accent: string;
    /** The couple as the visitor typed them (or the demo couple). */
    couple: {
        first: string;
        second: string;
        monogram: string;
        url: string;
        date: string;
    };
}>();

const site = studio.site;
</script>

<!-- The demo couple's wedding site in a browser window (illustration only). -->
<template>
    <figure class="showcase-window" aria-hidden="true">
        <div class="showcase-bar">
            <span class="flex gap-1.5">
                <span v-for="dot in 3" :key="dot" class="showcase-bar-dot" />
            </span>
            <span class="showcase-url">{{ couple.url }}</span>
        </div>

        <div class="showcase-site" data-theme="ivory" :data-accent="accent">
            <div class="showcase-nav">
                <span class="showcase-monogram">{{ couple.monogram }}</span>
                <span class="hidden gap-6 text-sm md:flex">
                    <span v-for="link in site.nav" :key="link">{{ link }}</span>
                </span>
                <span class="showcase-action">{{ site.action }}</span>
            </div>

            <div class="showcase-hero">
                <ResponsivePhoto
                    :photo="site.photos.hero"
                    sizes="(min-width: 1280px) 72rem, 94vw"
                    class="showcase-hero-photo"
                />
                <div class="showcase-hero-text">
                    <p class="showcase-names">
                        {{ couple.first }}
                        <span class="showcase-amp">&amp;</span>
                        {{ couple.second }}
                    </p>
                    <p class="text-sm font-medium sm:text-base">
                        {{ couple.date }} · {{ site.place }}
                    </p>
                </div>
            </div>

            <ShowcaseDetails />
        </div>
    </figure>
</template>
