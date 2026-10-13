<script setup lang="ts">
import { PanelLeftClose, PanelLeftOpen } from '@lucide/vue';
import { useAppNav } from '@/composables/useAppNav';

const { expanded, drawer, toggle } = useAppNav();
</script>

<!-- Collapses the nav on desktop; opens it over the page on smaller screens. -->
<template>
    <button
        type="button"
        class="app-nav-toggle"
        aria-controls="app-nav"
        :aria-expanded="drawer || expanded"
        aria-label="Navigation ein- oder ausblenden"
        @click="toggle"
    >
        <PanelLeftClose v-if="drawer" class="size-4" />
        <PanelLeftOpen v-else class="size-4" />
    </button>
</template>
