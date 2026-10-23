<script setup lang="ts">
import { Check } from '@lucide/vue';
import PricingPlan from '@/components/marketing/PricingPlan.vue';
import { pricing } from '@/content/landing';
import { chf } from '@/content/publish';
import { show as topic } from '@/routes/topics';
import type { PlanKey } from '@/types';
import type { LandingPrices } from '@/types/landing';

defineProps<{ prices: LandingPrices }>();

const order: PlanKey[] = ['signature', 'atelier', 'couture'];
</script>

<!--
    «Was es kostet»: what stays free on the left, the three plans as a
    price list on the right, the extras below with their prices. The prices
    are the ones checkout charges (config/hereby.php).
-->
<template>
    <section id="preise" class="page-container section flex flex-col gap-14">
        <header v-reveal class="flex max-w-3xl reveal flex-col gap-6">
            <h2 class="headline">{{ pricing.title }}</h2>
            <p class="lede">{{ pricing.lede }}</p>
        </header>

        <div class="pricing-grid">
            <aside class="pricing-free">
                <p class="title">{{ pricing.free }}</p>
                <p class="pricing-figure">CHF 0</p>
                <ul class="pricing-features">
                    <li
                        v-for="item in pricing.freeItems"
                        :key="item"
                        class="flex gap-2.5"
                    >
                        <Check
                            class="mt-1 size-4 shrink-0 text-brand"
                            aria-hidden="true"
                        />
                        {{ item }}
                    </li>
                </ul>
            </aside>
            <ol class="pricing-plans">
                <PricingPlan
                    v-for="plan in order"
                    :key="plan"
                    :plan="plan"
                    :price="prices.plans[plan]"
                />
            </ol>
        </div>

        <div class="pricing-extras">
            <h3 class="title">{{ pricing.extrasTitle }}</h3>
            <dl class="pricing-list">
                <div
                    v-for="extra in pricing.extras"
                    :key="extra.addon"
                    class="pricing-row"
                >
                    <dt>{{ extra.name }}</dt>
                    <dd>
                        <template v-if="extra.addon === 'bespoke'"
                            >{{ pricing.from }}
                        </template>
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
