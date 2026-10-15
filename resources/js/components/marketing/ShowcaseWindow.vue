<script setup lang="ts">
import ShowcaseSite from '@/components/marketing/ShowcaseSite.vue';
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';

withDefaults(
    defineProps<{
        /** Which of the three templates this window shows. */
        template: string;
        /** The slide in front: only its 3D layer renders. */
        active: boolean;
        /** Accent slug; only accent colours follow it, never the page ground. */
        accent: string;
        /** The couple as the visitor typed them (or the demo couple). */
        couple: ShowcaseCoupleSite;
        /** The carousel's still window; the popup's scrolls. */
        preview?: boolean;
    }>(),
    { preview: false },
);
</script>

<!--
    One template of the demo couple's site in a browser window. In the
    carousel it stands still; in the popup it scrolls like a real browser.
-->
<template>
    <figure class="showcase-window" :data-preview="preview">
        <div class="showcase-bar">
            <span class="flex gap-1.5" aria-hidden="true">
                <span v-for="dot in 3" :key="dot" class="showcase-bar-dot" />
            </span>
            <span class="showcase-url" aria-hidden="true">{{
                couple.url
            }}</span>
            <span class="showcase-bar-end">
                <slot name="bar" />
            </span>
        </div>

        <ShowcaseSite
            :template="template"
            :active="active"
            :accent="accent"
            :couple="couple"
            :preview="preview"
        />
        <slot />
    </figure>
</template>
