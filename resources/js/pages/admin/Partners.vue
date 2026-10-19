<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import PageMasthead from '@/components/PageMasthead.vue';
import PartnerCreateForm from '@/components/admin/PartnerCreateForm.vue';
import PartnerImportForm from '@/components/admin/PartnerImportForm.vue';
import PartnerEditDialog from '@/components/admin/PartnerEditDialog.vue';
import PartnerRow from '@/components/admin/PartnerRow.vue';
import { adminPartners as copy } from '@/content/partners';
import { index } from '@/routes/admin/partners';
import type { AdminPartner, PartnerType } from '@/types';

defineOptions({
    layout: { breadcrumbs: [{ title: 'Partner', href: index() }] },
});

defineProps<{ partners: AdminPartner[] }>();

const types: Record<PartnerType, string> = {
    venue: 'Location',
    photographer: 'Fotograf:in',
    planner: 'Planer:in',
};

const editing = ref<AdminPartner | null>(null);
</script>

<!-- The team's partners: open an account or a prospect, pitch a demo, correct it. -->
<template>
    <Head :title="copy.title" />

    <div class="app-page admin-page">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <PartnerCreateForm :types="types" />
        <PartnerImportForm />

        <p v-if="!partners.length" class="admin-empty">{{ copy.empty }}</p>
        <ul v-else class="admin-ledger">
            <PartnerRow
                v-for="partner in partners"
                :key="partner.id"
                :partner="partner"
                @edit="editing = partner"
            />
        </ul>

        <PartnerEditDialog
            v-if="editing"
            :key="editing.id"
            :partner="editing"
            :types="types"
            :open="true"
            @update:open="(value) => !value && (editing = null)"
        />
    </div>
</template>
