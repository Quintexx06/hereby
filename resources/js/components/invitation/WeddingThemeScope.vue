<script setup lang="ts">
import { computed } from 'vue';
import { accentVars } from '@/lib/accent';
import type { WeddingTheme } from '@/types';

const { fill = true, accent = null } = defineProps<{
    theme: WeddingTheme;
    lang: string;
    /** The couple's own accent colour; replaces the theme's hue. */
    accent?: string | null;
    /** Full-screen guest page; false for embedded previews. */
    fill?: boolean;
}>();

const custom = computed(() => accentVars(accent));
</script>

<!-- Applies a couple's theme tokens to everything inside (ADR 0004, 0012). -->
<template>
    <div
        :data-theme="theme"
        :data-custom-accent="custom ? '' : undefined"
        :style="custom ?? undefined"
        :lang="lang"
        :class="{ 'min-h-svh': fill }"
    >
        <slot />
    </div>
</template>
