<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import CopyLink from '@/components/CopyLink.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { dashboardPage } from '@/content/dashboard';
import { newspaperPage as copy } from '@/content/extras';
import { dashboard } from '@/routes';
import { show as extras } from '@/routes/weddings/extras';

defineProps<{
    wedding: { id: number; couple_names: string };
    unlocked: boolean;
    articles: number;
    contributeLink: string | null;
    witnessLink: string | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: copy.title, href: '' },
        ],
    },
});
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <div v-if="!unlocked" class="flex max-w-3xl flex-col items-start gap-5">
            <p class="publish-note">{{ copy.locked }}</p>
            <Link :href="extras(wedding.id)" class="text-action hit-area">
                {{ copy.unlock }}
            </Link>
        </div>

        <template v-else>
            <p class="caption">{{ copy.count(articles) }}</p>
            <CopyLink
                v-if="contributeLink"
                id="newspaper-link"
                :link="contributeLink"
                :label="copy.contributeLabel"
                :hint="copy.contributeHint"
            />
            <CopyLink
                v-if="witnessLink"
                id="newspaper-witness-link"
                :link="witnessLink"
                :label="copy.witnessLabel"
                :hint="copy.witnessHint"
            />
        </template>
    </div>
</template>
