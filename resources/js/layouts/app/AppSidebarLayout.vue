<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, watchEffect } from 'vue';
import AppContent from '@/components/AppContent.vue';
import AppFocusBar from '@/components/AppFocusBar.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import { Toaster } from '@/components/ui/sonner';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

/*
 * The sidebar only earns its place once there is something to manage: an
 * active wedding, or the team's area. Before that the page stays focused.
 */
const page = usePage();
const hasSidebar = computed(
    () =>
        page.props.currentWedding?.status === 'active' ||
        page.props.adminInbox !== null,
);

/* The page scrolls on the night frame then; base.css gives it a light scrollbar. */
const shell = (on: boolean) =>
    typeof document !== 'undefined' &&
    document.documentElement.classList.toggle('app-shell', on);
watchEffect(() => shell(hasSidebar.value));
onBeforeUnmount(() => shell(false));
</script>

<template>
    <AppShell v-if="hasSidebar" variant="sidebar">
        <AppSidebar />
        <AppContent
            variant="sidebar"
            class="app-workspace min-w-0 overflow-x-clip"
        >
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
        <Toaster />
    </AppShell>
    <div v-else class="app-focus">
        <AppFocusBar />
        <main class="min-w-0 flex-1">
            <slot />
        </main>
        <Toaster />
    </div>
</template>
