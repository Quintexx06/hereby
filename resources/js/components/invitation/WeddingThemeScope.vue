<script setup lang="ts">
import type { WeddingTheme } from '@/types';

defineProps<{
    theme: WeddingTheme;
    lang: string;
}>();
</script>

<!-- Applies a couple's theme tokens to everything inside (ADR 0004). -->
<template>
    <div :data-theme="theme" :lang="lang" class="min-h-svh">
        <slot />
    </div>
</template>
