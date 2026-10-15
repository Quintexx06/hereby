<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useTemplateRef } from 'vue';
import PageMasthead from '@/components/PageMasthead.vue';
import PartnerCodeForm from '@/components/partners/couple/PartnerCodeForm.vue';
import PartnerInviteForm from '@/components/partners/couple/PartnerInviteForm.vue';
import PartnerRoleCard from '@/components/partners/couple/PartnerRoleCard.vue';
import PartnerSuggestion from '@/components/partners/couple/PartnerSuggestion.vue';
import { dashboardPage } from '@/content/dashboard';
import { weddingPartners as copy } from '@/content/partners';
import { dashboard } from '@/routes';
import { destroy } from '@/routes/weddings/partners';
import { destroy as withdrawInvite } from '@/routes/weddings/partners/invites';
import type {
    ConnectedPartner,
    PartnerInviteRow,
    PartnerType,
    SuggestedPartner,
} from '@/types';

const props = defineProps<{
    wedding: { id: number };
    partners: ConnectedPartner[];
    invites: PartnerInviteRow[];
    suggested: SuggestedPartner | null;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: copy.title, href: '' },
        ],
    },
});

const roles: PartnerType[] = ['venue', 'photographer', 'planner'];
const inviteForm = useTemplateRef('inviteForm');

function remove(partner: ConnectedPartner): void {
    router.delete(destroy.url([props.wedding.id, partner.id]), {
        preserveScroll: true,
    });
}

function withdraw(invite: PartnerInviteRow): void {
    router.delete(withdrawInvite.url([props.wedding.id, invite.id]), {
        preserveScroll: true,
    });
}
</script>

<!--
    The couple's partners, one card per role: who is connected, who is
    invited, and two ways in (a code, or an invitation by mail).
-->
<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <PartnerSuggestion
            v-if="suggested"
            :wedding-id="wedding.id"
            :partner="suggested"
        />

        <section class="partner-roles" v-reveal>
            <PartnerRoleCard
                v-for="type in roles"
                :key="type"
                :type="type"
                :partners="partners.filter((p) => p.type === type)"
                :invites="invites.filter((i) => i.type === type)"
                @invite="inviteForm?.choose($event)"
                @remove="remove"
                @withdraw="withdraw"
            />
        </section>

        <div class="partner-panels">
            <PartnerCodeForm :wedding-id="wedding.id" />
            <PartnerInviteForm ref="inviteForm" :wedding-id="wedding.id" />
        </div>
    </div>
</template>
