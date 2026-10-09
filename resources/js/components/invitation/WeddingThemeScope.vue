<script setup lang="ts">
import type { WeddingTheme } from '@/types';

const { fill = true } = defineProps<{
    theme: WeddingTheme;
    lang: string;
    /** Full-screen guest page; false for embedded previews. */
    fill?: boolean;
}>();
</script>

<!-- Applies a couple's theme tokens to everything inside (ADR 0004). -->
<template>
    <div :data-theme="theme" :lang="lang" :class="{ 'min-h-svh': fill }">
        <slot />
    </div>
</template>
