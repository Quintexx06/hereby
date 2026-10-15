<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import BriefCard from '@/components/admin/BriefCard.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { adminBriefs as copy } from '@/content/bespoke';
import { index } from '@/routes/admin/briefs';
import type { AdminBrief } from '@/types';

defineOptions({
    layout: { breadcrumbs: [{ title: 'Massanfertigung', href: index() }] },
});

defineProps<{
    briefs: AdminBrief[];
    statuses: { value: string; label: string }[];
}>();
</script>

<!-- The team's «Massanfertigung» queue: every sent brief, newest work first. -->
<template>
    <Head :title="copy.title" />

    <div class="app-page admin-page">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <p v-if="!briefs.length" class="admin-empty">{{ copy.empty }}</p>
        <BriefCard
            v-for="brief in briefs"
            :key="brief.id"
            :brief="brief"
            :statuses="statuses"
        />
    </div>
</template>
