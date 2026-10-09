<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import { account, appearanceCopy } from '@/content/account';
import { edit } from '@/routes/appearance';

defineOptions({
    layout: {
        breadcrumbs: [{ title: account.title, href: edit() }],
    },
});
</script>

<template>
    <Head :title="appearanceCopy.title" />

    <section class="settings-section">
        <Heading
            variant="small"
            :title="appearanceCopy.title"
            :description="appearanceCopy.lede"
        />
        <AppearanceTabs />
    </section>
</template>
