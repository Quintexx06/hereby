<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CopyLink from '@/components/CopyLink.vue';
import LockedPreview from '@/components/locked/LockedPreview.vue';
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

        <LockedPreview
            v-if="!unlocked"
            kind="newspaper"
            :note="copy.locked"
            :action="copy.unlock"
            :href="extras(wedding.id).url"
        />

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
