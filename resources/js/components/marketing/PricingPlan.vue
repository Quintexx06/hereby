<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { pricing } from '@/content/landing';
import { chf, plans } from '@/content/publish';
import { register } from '@/routes';

defineProps<{ plan: 'atelier' | 'couture'; price: number }>();
</script>

<!--
    One of the two bigger packages, as a column under a rule: its name and
    price, what it is for, what it holds (the same lines couples read at
    checkout), and the way to start.
-->
<template>
    <li class="pricing-plan" :data-plan="plan">
        <div class="flex items-baseline justify-between gap-4">
            <h4 class="title">{{ plans[plan].name }}</h4>
            <p class="pricing-figure">
                <span v-if="plan === 'couture'" class="pricing-from">{{
                    pricing.from
                }}</span>
                {{ chf(price) }}
            </p>
        </div>
        <p class="max-w-[40ch] text-muted-foreground">
            {{ pricing.lines[plan] }}
        </p>
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
