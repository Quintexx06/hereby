<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import DashboardDraft from '@/components/dashboard/DashboardDraft.vue';
import DashboardStart from '@/components/dashboard/DashboardStart.vue';
import InvitationPanel from '@/components/dashboard/InvitationPanel.vue';
import ProgrammeSummary from '@/components/dashboard/ProgrammeSummary.vue';
import ReplySummary from '@/components/dashboard/ReplySummary.vue';
import TodayList from '@/components/dashboard/TodayList.vue';
import { dashboardPage } from '@/content/dashboard';
import { formatDate } from '@/lib/format';
import { dashboard } from '@/routes';
import type { DashboardWedding, WeddingOverview } from '@/types';

const props = defineProps<{
    wedding: DashboardWedding | null;
    overview: WeddingOverview | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: dashboardPage.title, href: dashboard() }],
    },
});

const subtitle = computed(() => {
    if (!props.wedding?.date) {
        return '';
    }

    /* The countdown lives in the sidebar; here, the date and the place. */
    return [formatDate(props.wedding.date, 'de-CH'), props.wedding.venue]
        .filter(Boolean)
        .join(', ');
});
</script>

<template>
    <Head :title="dashboardPage.title" />

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

            <div class="dashboard-grid">
                <div class="flex flex-col gap-14">
                    <TodayList
                        :wedding-id="wedding.id"
                        :actions="overview.actions"
                    />
                    <ReplySummary
                        :wedding-id="wedding.id"
                        :overview="overview"
                    />
                </div>
                <div class="flex flex-col gap-14">
                    <InvitationPanel
                        :wedding="wedding"
                        :events="overview.events"
                    />
                    <ProgrammeSummary
                        :events="overview.events"
                        :has-replies="overview.replies.answered > 0"
                    />
                </div>
            </div>
        </template>
    </div>
</template>
