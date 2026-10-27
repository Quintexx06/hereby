<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import PageMasthead from '@/components/PageMasthead.vue';
import PlanCard from '@/components/publish/PlanCard.vue';
import { dashboardPage } from '@/content/dashboard';
import { publishPage as copy } from '@/content/publish';
import { dashboard } from '@/routes';
import { preview } from '@/routes/weddings';
import { store } from '@/routes/weddings/publish';
import type { PlanKey, PlanOffer, PublishWedding } from '@/types';

const props = defineProps<{
    wedding: PublishWedding;
    plans: PlanOffer[];
    checkoutReady: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: 'Online stellen', href: '' },
        ],
    },
});

const form = useForm<{ plan: PlanKey | null }>({ plan: null });
const canBuy = computed(() => props.checkoutReady && props.wedding.active);

/* The server answers with Stripe's Checkout URL; Inertia follows it. */
function buy(plan: PlanKey): void {
    form.plan = plan;
    form.post(store.url(props.wedding.id), { preserveScroll: true });
}
</script>

<!--
    The paywall as a calm page: the couple's own site in a window, the plans
    side by side, what stays free, and how payment works. Nothing locked
    while building; this is where the links open.
-->
<template>
    <Head :title="copy.title" />

    <div class="app-page gap-12">
        <PageMasthead
            :title="wedding.published ? copy.titlePublished : copy.title"
            :lede="wedding.published ? copy.ledePublished : copy.lede"
        >
            <template #aside>
                <div class="publish-window" :aria-label="copy.previewLabel">
                    <span class="publish-window-bar" aria-hidden="true">
                        <span /><span /><span />
                    </span>
                    <iframe
                        :src="preview.url(wedding.id)"
                        :title="copy.previewLabel"
                        loading="lazy"
                        tabindex="-1"
                        class="publish-window-frame"
                    />
                </div>
            </template>
        </PageMasthead>

        <p v-if="!wedding.active" class="publish-note">
            {{ copy.finishSetup }}
        </p>
        <p
            v-else-if="!checkoutReady && plans.some((plan) => !plan.owned)"
            class="publish-note"
        >
            {{ copy.notReady }}
            <a href="mailto:info@hereby.ch" class="link-underline"
                >info@hereby.ch</a
            >
        </p>

        <section class="plans">
            <PlanCard
                v-for="offer in plans"
                :key="offer.key"
                :offer="offer"
                :can-buy="canBuy"
                :busy="form.processing && form.plan === offer.key"
                :upgrading="wedding.plan !== null"
                @buy="buy(offer.key)"
            />
        </section>
        <p v-if="form.errors.plan" class="text-sm text-destructive">
            {{ form.errors.plan }}
        </p>

        <div class="publish-fine">
            <p>{{ copy.free }}</p>
            <p>{{ copy.fine }}</p>
        </div>
    </div>
</template>
