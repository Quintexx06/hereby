<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DashboardDraft from '@/components/dashboard/DashboardDraft.vue';
import DashboardHero from '@/components/dashboard/DashboardHero.vue';
import DashboardShortcuts from '@/components/dashboard/DashboardShortcuts.vue';
import DashboardStart from '@/components/dashboard/DashboardStart.vue';
import DashboardStats from '@/components/dashboard/DashboardStats.vue';
import InvitationPanel from '@/components/dashboard/InvitationPanel.vue';
import ProgrammeSummary from '@/components/dashboard/ProgrammeSummary.vue';
import ReplySummary from '@/components/dashboard/ReplySummary.vue';
import TodayList from '@/components/dashboard/TodayList.vue';
import { dashboardPage } from '@/content/dashboard';
import { dashboard } from '@/routes';
import type { DashboardWedding, WeddingOverview } from '@/types';

defineProps<{
    wedding: DashboardWedding | null;
    overview: WeddingOverview | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: dashboardPage.title, href: dashboard() }],
    },
});
</script>

<template>
    <Head :title="dashboardPage.title" />

    <div class="app-page dashboard-page">
        <DashboardStart v-if="!wedding" />
        <DashboardDraft
            v-else-if="wedding.status === 'draft'"
            :wedding="wedding"
        />

        <template v-else-if="overview">
            <DashboardHero :wedding="wedding" />
            <DashboardStats :overview="overview" />

            <div class="dashboard-columns">
                <div class="flex min-w-0 flex-col">
                    <TodayList
                        :wedding-id="wedding.id"
                        :actions="overview.actions"
                    />
                    <ReplySummary
                        :wedding-id="wedding.id"
                        :overview="overview"
                    />
                </div>
                <InvitationPanel
                    :wedding="wedding"
                    :events="overview.events"
                    class="lg:sticky lg:top-20"
                />
            </div>

            <ProgrammeSummary
                :events="overview.events"
                :has-replies="overview.replies.answered > 0"
            />
            <DashboardShortcuts :wedding-id="wedding.id" />
        </template>
    </div>
</template>
