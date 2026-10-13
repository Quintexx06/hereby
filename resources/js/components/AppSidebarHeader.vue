<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import NavToggle from '@/components/sidebar/NavToggle.vue';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-border px-6"
    >
        <div class="flex items-center gap-2">
            <NavToggle />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <!-- On phones the sidebar is hidden, so the couple's names stay in view. -->
        <p
            v-if="
                page.props.currentWedding?.couple_names &&
                page.component !== 'Dashboard'
            "
            class="ml-auto truncate font-display text-lg font-semibold tracking-[-0.02em] sm:hidden"
        >
            {{ page.props.currentWedding.couple_names }}
        </p>
    </header>
</template>
