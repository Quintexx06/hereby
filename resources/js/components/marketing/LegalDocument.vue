<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SiteHeader from '@/components/marketing/SiteHeader.vue';
import type { LegalPage } from '@/content/legal';

defineProps<{
    page: LegalPage;
}>();
</script>

<!-- Privacy policy and imprint: a night header band, then a calm reading column. -->
<template>
    <Head :title="page.title" />
    <div class="legal-band stage">
        <SiteHeader />
        <div class="page-container relative pt-36 pb-16 sm:pt-44">
            <h1 class="display">{{ page.title }}</h1>
            <p class="caption mt-4">{{ page.updated }}</p>
        </div>
    </div>

    <article class="page-container legal-body">
        <p class="legal-draft">{{ page.draftNote }}</p>
        <section
            v-for="section in page.sections"
            :key="section.heading"
            class="legal-section"
        >
            <h2 class="title">{{ section.heading }}</h2>
            <div class="flex flex-col gap-3">
                <p
                    v-for="paragraph in section.paragraphs"
                    :key="paragraph"
                    class="body-copy"
                >
                    {{ paragraph }}
                </p>
            </div>
        </section>
    </article>
</template>
