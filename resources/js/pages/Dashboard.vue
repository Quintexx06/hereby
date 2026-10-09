<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import DashboardDraft from '@/components/dashboard/DashboardDraft.vue';
import DashboardStart from '@/components/dashboard/DashboardStart.vue';
import ProgrammeSummary from '@/components/dashboard/ProgrammeSummary.vue';
import ReplySummary from '@/components/dashboard/ReplySummary.vue';
import TodayList from '@/components/dashboard/TodayList.vue';
import { active } from '@/content/dashboard';
import { formatDate } from '@/lib/format';
import { dashboard } from '@/routes';
import type { DashboardWedding, WeddingOverview } from '@/types';

const props = defineProps<{
    wedding: DashboardWedding | null;
    overview: WeddingOverview | null;
}>();

defineOptions({
    layout: { breadcrumbs: [{ title: 'Übersicht', href: dashboard() }] },
});

const subtitle = computed(() => {
    if (!props.wedding?.date) {
        return '';
    }

    const parts = [
        formatDate(props.wedding.date, 'de-CH'),
        props.wedding.venue,
    ];

    if (
        props.overview?.days_left !== null &&
        props.overview?.days_left !== undefined
    ) {
        parts.push(active.daysLeft(props.overview.days_left));
    }

    return parts.filter(Boolean).join(', ');
});
</script>

<template>
    <Head title="Übersicht" />

    <div class="app-page">
        <DashboardStart v-if="!wedding" />
        <DashboardDraft
            v-else-if="wedding.status === 'draft'"
            :wedding="wedding"
        />

        <template v-else-if="overview">
            <header class="flex flex-col gap-3">
                <h1 class="app-title">{{ wedding.couple_names }}</h1>
                <p class="lede">{{ subtitle }}</p>
            </header>

            <TodayList :wedding-id="wedding.id" :actions="overview.actions" />

            <div
                class="grid gap-14 lg:grid-cols-[minmax(0,6fr)_minmax(0,5fr)] lg:gap-16"
            >
                <ReplySummary :wedding-id="wedding.id" :overview="overview" />
                <ProgrammeSummary :events="overview.events" />
            </div>
        </template>
    </div>
</template>
