<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ActsSection from '@/components/marketing/ActsSection.vue';
import BespokeSection from '@/components/marketing/BespokeSection.vue';
import ClosingSection from '@/components/marketing/ClosingSection.vue';
import FloatingNav from '@/components/marketing/FloatingNav.vue';
import FaqSection from '@/components/marketing/FaqSection.vue';
import GuestSection from '@/components/marketing/GuestSection.vue';
import HeroSection from '@/components/marketing/HeroSection.vue';
import IntroSection from '@/components/marketing/IntroSection.vue';
import MarqueeBand from '@/components/marketing/MarqueeBand.vue';
import PremiumSection from '@/components/marketing/PremiumSection.vue';
import PricingSection from '@/components/marketing/PricingSection.vue';
import StepsSection from '@/components/marketing/StepsSection.vue';
import StruckSection from '@/components/marketing/StruckSection.vue';
import StudioSection from '@/components/marketing/StudioSection.vue';
import type { FaqItem, LandingPrices, LandingSeo } from '@/types/landing';

defineProps<{
    seo: LandingSeo;
    faq: FaqItem[];
    prices: LandingPrices;
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
    <HeroSection :from="prices.plans.signature" />
    <StudioSection />
    <BespokeSection :price="prices.addons.bespoke" />
    <IntroSection />
    <GuestSection />
    <StepsSection />
    <ActsSection />
    <MarqueeBand />
    <PremiumSection :prices="prices" />
    <PricingSection :prices="prices" />
    <StruckSection />
    <FaqSection :items="faq" />
    <ClosingSection />
</template>
