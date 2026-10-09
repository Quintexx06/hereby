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
        <header class="flex max-w-2xl flex-col gap-3">
            <h1 class="app-title">{{ copy.title }}</h1>
            <p class="text-muted-foreground">{{ copy.lede }}</p>
        </header>

        <section
            v-for="group in groups"
            :key="group.key"
            class="flex flex-col gap-5"
        >
            <h2 class="app-section-title">
                {{ group.title }}
                <span class="text-muted-foreground tabular-nums">{{
                    group.items.length
                }}</span>
            </h2>
            <p v-if="group.items.length === 0" class="admin-empty">
                {{ group.empty }}
            </p>
            <ul v-else class="flex flex-col gap-3">
                <InquiryCard
                    v-for="inquiry in group.items"
                    :key="inquiry.id"
                    :inquiry="inquiry"
                />
            </ul>
        </section>
    </div>
</template>
