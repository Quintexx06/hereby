<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { bespoke as copy } from '@/content/bespoke';
import { store } from '@/routes/weddings/extras';
import { show as publish } from '@/routes/weddings/publish';
import type { BespokeWedding } from '@/types';

const props = defineProps<{
    wedding: BespokeWedding;
    checkoutReady: boolean;
}>();

function book(): void {
    router.post(store.url(props.wedding.id), { addon: 'bespoke' });
}
</script>

<!-- What «Massanfertigung» is, in three steps, with its price and booking. -->
<template>
    <section class="bespoke-offer stage">
        <ol class="bespoke-steps">
            <li
                v-for="(step, index) in copy.steps"
                :key="step.title"
                class="bespoke-step"
            >
                <span class="bespoke-step-number">{{ index + 1 }}</span>
                <p class="font-display text-xl font-semibold">
                    {{ step.title }}
                </p>
                <p class="text-sm opacity-75">{{ step.body }}</p>
            </li>
        </ol>
        <div class="bespoke-offer-foot">
            <p class="font-display text-2xl font-semibold">
                {{ copy.price(wedding.price) }}
            </p>
            <p v-if="wedding.owned" class="bespoke-owned">
                <Check class="size-4" /> {{ copy.owned }}
            </p>
            <Button
                v-else-if="wedding.buyable && checkoutReady"
                size="pill"
                variant="secondary"
                @click="book"
            >
                {{ copy.book }}
            </Button>
            <Link
                v-else-if="!wedding.buyable"
                :href="publish(wedding.id)"
                class="text-action"
            >
                {{ copy.needsPlan }}
            </Link>
            <p v-else class="text-sm opacity-75">{{ copy.paymentsOff }}</p>
        </div>
    </section>
</template>
