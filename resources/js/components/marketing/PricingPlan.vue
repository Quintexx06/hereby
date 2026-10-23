<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { pricing } from '@/content/landing';
import { chf, plans } from '@/content/publish';
import { register } from '@/routes';
import type { PlanKey } from '@/types';

defineProps<{ plan: PlanKey; price: number }>();
</script>

<!--
    One plan as a line of the price list: its name and what it is for, the
    price once, the way to start, and what it holds (the same lines couples
    read at checkout).
-->
<template>
    <li class="pricing-plan" :data-plan="plan">
        <div class="flex flex-col gap-2">
            <h3 class="title">{{ plans[plan].name }}</h3>
            <p class="max-w-[34ch] text-muted-foreground">
                {{ pricing.lines[plan] }}
            </p>
        </div>
        <div class="pricing-price">
            <p class="pricing-figure">
                <span v-if="plan === 'couture'" class="pricing-from">{{
                    pricing.from
                }}</span>
                {{ chf(price) }}
            </p>
            <p class="caption">{{ pricing.once }}</p>
        </div>
        <ul class="pricing-features">
            <li
                v-for="feature in plans[plan].features"
                :key="feature.text"
                class="flex gap-2.5"
            >
                <Check
                    class="mt-1 size-4 shrink-0 text-brand"
                    aria-hidden="true"
                />
                {{ feature.text }}
            </li>
        </ul>
        <Button
            as-child
            size="pill"
            :variant="plan === 'atelier' ? 'default' : 'outline'"
            class="pricing-cta"
        >
            <a v-if="plan === 'couture'" :href="pricing.askHref">{{
                pricing.ask
            }}</a>
            <Link v-else :href="register()">{{ pricing.start }}</Link>
        </Button>
    </li>
</template>
