<script setup lang="ts">
import PricingLead from '@/components/marketing/PricingLead.vue';
import PricingPlan from '@/components/marketing/PricingPlan.vue';
import { pricing } from '@/content/landing';
import { chf } from '@/content/publish';
import { show as topic } from '@/routes/topics';
import type { LandingPrices } from '@/types/landing';

defineProps<{ prices: LandingPrices }>();

const more = ['atelier', 'couture'] as const;
</script>

<!--
    «Was es kostet»: the smallest package first and large, the two bigger
    ones side by side below it, then the extras one by one with their
    prices. The prices are the ones checkout charges (config/hereby.php).
-->
<template>
    <section id="preise" class="page-container section flex flex-col gap-12">
        <header v-reveal class="flex max-w-3xl reveal flex-col gap-6">
            <h2 class="headline">{{ pricing.title }}</h2>
            <p class="lede">{{ pricing.lede }}</p>
        </header>

        <PricingLead :price="prices.plans.signature" />

        <div class="flex flex-col gap-8 pt-4">
            <h3 class="title">{{ pricing.moreTitle }}</h3>
            <ol class="pricing-more">
                <PricingPlan
                    v-for="plan in more"
                    :key="plan"
                    :plan="plan"
                    :price="prices.plans[plan]"
                />
            </ol>
        </div>

        <div class="flex flex-col gap-6 pt-4">
            <h3 class="title">{{ pricing.extrasTitle }}</h3>
            <dl class="pricing-extras">
                <div
                    v-for="extra in pricing.extras"
                    :key="extra.addon"
                    class="pricing-extra"
                >
                    <dt>{{ extra.name }}</dt>
                    <dd class="pricing-extra-price">
                        <span
                            v-if="extra.addon === 'bespoke'"
                            class="pricing-from"
                            >{{ pricing.from }}</span
                        >
                        {{ chf(prices.addons[extra.addon]) }}
                        <span v-if="extra.perYear" class="caption">{{
                            pricing.perYear
                        }}</span>
                    </dd>
                </div>
            </dl>
            <p class="caption max-w-2xl">
                {{ pricing.fine }}
                <a :href="topic.url('preise')" class="link-underline">{{
                    pricing.details
                }}</a>
            </p>
        </div>
    </section>
</template>
