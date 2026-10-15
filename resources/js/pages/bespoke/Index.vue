<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PageMasthead from '@/components/PageMasthead.vue';
import BespokeOffer from '@/components/bespoke/BespokeOffer.vue';
import BriefForm from '@/components/bespoke/BriefForm.vue';
import BriefPhotos from '@/components/bespoke/BriefPhotos.vue';
import { bespoke as copy } from '@/content/bespoke';
import { dashboardPage } from '@/content/dashboard';
import { dashboard } from '@/routes';
import { preview } from '@/routes/weddings';
import type { BespokeWedding, Brief } from '@/types';

defineProps<{
    wedding: BespokeWedding;
    checkoutReady: boolean;
    brief: Brief;
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

<!--
    «Massanfertigung»: the offer, where the couple's site stands, and the
    brief the team builds from (colours, mood, links, inspiration).
-->
<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <BespokeOffer :wedding="wedding" :checkout-ready="checkoutReady" />

        <div v-if="brief.status !== 'draft'" class="brief-status" role="status">
            <p class="font-semibold">{{ copy.status[brief.status] }}</p>
            <p v-if="brief.team_note" class="text-sm">
                <span class="font-medium">{{ copy.teamNote }}:</span>
                {{ brief.team_note }}
            </p>
            <Link
                v-if="brief.status === 'ready'"
                :href="preview(wedding.id)"
                class="text-action self-start"
                >{{ copy.openPreview }}</Link
            >
        </div>

        <div class="brief-layout">
            <BriefForm
                :wedding-id="wedding.id"
                :brief="brief"
                :owned="wedding.owned"
            />
            <BriefPhotos :wedding-id="wedding.id" :photos="brief.photos" />
        </div>
    </div>
</template>
