<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import InquiryCard from '@/components/admin/InquiryCard.vue';
import { inquiriesCopy as copy } from '@/content/admin';
import { index } from '@/routes/admin/inquiries';
import type { AdminInquiry } from '@/types';

defineOptions({
    layout: { breadcrumbs: [{ title: 'Anfragen', href: index() }] },
});

const props = defineProps<{ inquiries: AdminInquiry[] }>();

const groups = computed(() => [
    {
        key: 'open',
        title: copy.open,
        empty: copy.emptyOpen,
        items: props.inquiries.filter((inquiry) => !inquiry.answered),
    },
    {
        key: 'answered',
        title: copy.answered,
        empty: copy.emptyAnswered,
        items: props.inquiries.filter((inquiry) => inquiry.answered),
    },
]);
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page admin-page">
        <header class="masthead">
            <div class="max-w-2xl">
                <h1 class="masthead-title">{{ copy.title }}</h1>
                <p class="masthead-meta text-pretty">{{ copy.lede }}</p>
            </div>
        </header>

        <section
            v-for="group in groups"
            :key="group.key"
            class="ledger-section"
        >
            <h2 class="ledger-title">
                {{ group.title }}
                <span class="text-muted-foreground tabular-nums">{{
                    group.items.length
                }}</span>
            </h2>
            <p v-if="group.items.length === 0" class="admin-empty">
                {{ group.empty }}
            </p>
            <ul v-else class="admin-ledger">
                <InquiryCard
                    v-for="inquiry in group.items"
                    :key="inquiry.id"
                    :inquiry="inquiry"
                />
            </ul>
        </section>
    </div>
</template>
