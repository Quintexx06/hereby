<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, watchEffect } from 'vue';
import AppFocusBar from '@/components/AppFocusBar.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import ScrollRail from '@/components/sidebar/ScrollRail.vue';
import { Toaster } from '@/components/ui/sonner';
import { provideAppNav } from '@/composables/useAppNav';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

/*
 * The sidebar only earns its place once there is something to manage: an
 * active wedding, the team's area, or a partner's weddings. Before that the
 * page stays focused.
 */
const page = usePage();
const hasSidebar = computed(
    () =>
        page.props.currentWedding?.status === 'active' ||
        page.props.adminInbox !== null ||
        page.props.partner !== null,
);
const { expanded, drawer, close } = provideAppNav(page.props.sidebarOpen);

/* The page scrolls on the night frame then, with its own scroll rail. */
const shell = (on: boolean) =>
    typeof document !== 'undefined' &&
    document.documentElement.classList.toggle('app-shell', on);
watchEffect(() => shell(hasSidebar.value));
onBeforeUnmount(() => shell(false));
</script>

<template>
    <div
        v-if="hasSidebar"
        class="app-frame"
        :data-expanded="expanded"
        :data-drawer="drawer"
    >
        <AppSidebar />
        <div class="app-nav-backdrop" aria-hidden="true" @click="close" />
        <main class="app-main app-workspace">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </main>
        <ScrollRail />
        <Toaster />
    </div>
    <div v-else class="app-focus">
        <AppFocusBar />
        <main class="min-w-0 flex-1">
            <slot />
        </main>
        <Toaster />
    </div>
</template>
