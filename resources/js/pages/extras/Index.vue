<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AddonCard from '@/components/extras/AddonCard.vue';
import DomainForm from '@/components/extras/DomainForm.vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { dashboardPage } from '@/content/dashboard';
import { extrasPage as copy } from '@/content/extras';
import type { AddonKey, AddonOffer } from '@/content/extras';
import { dashboard } from '@/routes';
import { store } from '@/routes/weddings/extras';
import { edit as newspaper } from '@/routes/weddings/newspaper';

const props = defineProps<{
    wedding: {
        id: number;
        couple_names: string;
        plan: string | null;
        evening: boolean;
        memories_kept_until: string | null;
        domain: { name: string | null; connected: boolean } | null;
        newspaper: boolean;
    };
    addons: AddonOffer[];
    checkoutReady: boolean;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: copy.title, href: '' },
        ],
    },
});

const form = useForm<{ addon: AddonKey | null }>({ addon: null });

/* The server answers with Stripe's Checkout URL; Inertia follows it. */
function buy(addon: AddonKey): void {
    form.addon = addon;
    form.post(store.url(props.wedding.id), { preserveScroll: true });
}
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-12">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <p v-if="!checkoutReady" class="publish-note">{{ copy.notReady }}</p>
        <p v-if="wedding.memories_kept_until" class="publish-note">
            {{ copy.keptUntil(wedding.memories_kept_until) }}
        </p>

        <section class="plans md:grid-cols-2 xl:grid-cols-4">
            <AddonCard
                v-for="offer in addons"
                :key="offer.key"
                :offer="offer"
                :can-buy="checkoutReady"
                :busy="form.processing && form.addon === offer.key"
                :has-plan="wedding.plan !== null"
                @buy="buy(offer.key)"
            />
        </section>
        <p v-if="form.errors.addon" class="text-sm text-destructive">
            {{ form.errors.addon }}
        </p>

        <Link
            v-if="wedding.newspaper"
            :href="newspaper(wedding.id)"
            class="text-action hit-area self-start"
        >
            Zur Hochzeitszeitung
        </Link>
        <DomainForm
            v-if="wedding.domain"
            :wedding-id="wedding.id"
            :domain="wedding.domain"
        />
    </div>
</template>
