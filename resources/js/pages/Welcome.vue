<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ActsSection from '@/components/marketing/ActsSection.vue';
import ClosingSection from '@/components/marketing/ClosingSection.vue';
import FloatingNav from '@/components/marketing/FloatingNav.vue';
import FaqSection from '@/components/marketing/FaqSection.vue';
import GuestSection from '@/components/marketing/GuestSection.vue';
import HeroSection from '@/components/marketing/HeroSection.vue';
import IntroSection from '@/components/marketing/IntroSection.vue';
import MarqueeBand from '@/components/marketing/MarqueeBand.vue';
import StepsSection from '@/components/marketing/StepsSection.vue';
import StruckSection from '@/components/marketing/StruckSection.vue';
import StudioSection from '@/components/marketing/StudioSection.vue';
import type { FaqItem, LandingSeo } from '@/types/landing';

defineProps<{
    seo: LandingSeo;
    faq: FaqItem[];
}>();
</script>

<!-- Meta tags are also rendered server-side (partials/landing-seo.blade.php). -->
<template>
    <Head :title="seo.title">
        <meta
            head-key="description"
            name="description"
            :content="seo.description"
        />
    </Head>
    <FloatingNav />
    <HeroSection />
    <IntroSection />
    <GuestSection />
    <StepsSection />
    <ActsSection />
    <MarqueeBand />
    <StudioSection />
    <StruckSection />
    <FaqSection :items="faq" />
    <ClosingSection />
</template>
