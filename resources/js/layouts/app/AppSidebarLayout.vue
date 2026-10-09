<script setup lang="ts">
import AppTopBar from '@/components/AppTopBar.vue';
import { Toaster } from '@/components/ui/sonner';
import type { BreadcrumbItem } from '@/types';

/* Breadcrumbs stay accepted for pages that pass them; the top bar shows where you are. */
withDefaults(defineProps<{ breadcrumbs?: BreadcrumbItem[] }>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="app-frame">
        <AppTopBar />
        <main class="min-w-0 flex-1">
            <slot />
        </main>
        <Toaster />
    </div>
</template>
