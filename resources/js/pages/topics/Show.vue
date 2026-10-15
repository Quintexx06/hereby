<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import TopicFaq from '@/components/topics/TopicFaq.vue';
import TopicHero from '@/components/topics/TopicHero.vue';
import TopicRelated from '@/components/topics/TopicRelated.vue';
import TopicSectionBlock from '@/components/topics/TopicSectionBlock.vue';
import { Button } from '@/components/ui/button';
import { topics as copy } from '@/content/landing';
import { register } from '@/routes';
import type {
    FaqItem,
    LandingSeo,
    RelatedTopic,
    TopicPage,
} from '@/types/landing';

defineProps<{
    slug: string;
    page: TopicPage;
    seo: LandingSeo;
    faq: FaqItem[];
    related: RelatedTopic[];
}>();
</script>

<!--
    An SEO topic page (lang/de_CH/pages.php). Meta tags and FAQPage data are
    rendered server-side too (partials/landing-seo.blade.php).
-->
<template>
    <Head :title="seo.title">
        <meta
            head-key="description"
            name="description"
            :content="seo.description"
        />
    </Head>

    <TopicHero :label="page.label" :title="page.h1" :lede="page.lede" />

    <article class="page-container topic-body">
        <TopicSectionBlock
            v-for="section in page.sections"
            :key="section.title"
            :section="section"
        />
        <TopicFaq :items="faq" />
        <aside class="topic-cta stage">
            <p class="title max-w-[22ch] text-balance">{{ copy.closing }}</p>
            <Button as-child size="pill">
                <Link :href="register()">{{ copy.cta }}</Link>
            </Button>
        </aside>
    </article>

    <TopicRelated :items="related" />
</template>
