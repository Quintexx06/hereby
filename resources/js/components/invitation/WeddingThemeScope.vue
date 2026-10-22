<script setup lang="ts">
import type { WeddingTemplate } from '@/types';

const { fill = true } = defineProps<{
    template: WeddingTemplate;
    lang: string;
    /** Full-screen guest page; false for embedded previews. */
    fill?: boolean;
}>();
</script>

<!--
    Applies a design's paper, ink, accent and type to everything inside
    (ADR 0015): the invitation, the reply and every other guest page.
-->
<template>
    <div :data-theme="template" :lang="lang" :class="{ 'min-h-svh': fill }">
        <slot />
    </div>
</template>
