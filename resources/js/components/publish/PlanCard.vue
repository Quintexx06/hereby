<script setup lang="ts">
import { Check } from '@lucide/vue';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import CoutureRequest from '@/components/publish/CoutureRequest.vue';
import { Spinner } from '@/components/ui/spinner';
import { chf, plans, publishPage as copy } from '@/content/publish';
import type { PlanOffer } from '@/types';

const props = defineProps<{
    offer: PlanOffer;
    /** Buying is possible (setup finished, payments configured). */
    canBuy: boolean;
    busy: boolean;
    /** Something is already paid, so a purchase is an upgrade. */
    upgrading: boolean;
}>();

defineEmits<{ buy: [] }>();

const plan = computed(() => plans[props.offer.key]);
const featured = computed(() => props.offer.key === 'atelier');
</script>

<!-- One plan as a column: name, price, what it holds, one way to get it. -->
<template>
    <article class="plan" :data-featured="featured" :data-owned="offer.owned">
        <div class="flex items-baseline justify-between gap-3">
            <h2 class="plan-name">{{ plan.name }}</h2>
            <span v-if="featured" class="plan-flag">{{ copy.popular }}</span>
        </div>
        <p class="plan-price">
            <span v-if="!offer.online" class="text-base font-medium">{{
                copy.from
            }}</span>
            {{ chf(offer.price) }}
        </p>
        <p class="text-sm text-muted-foreground">{{ plan.line }}</p>

        <ul class="plan-features">
            <li v-for="feature in plan.features" :key="feature.text">
                <Check class="plan-tick" aria-hidden="true" />
                <span>
                    {{ feature.text }}
                    <span v-if="feature.soon" class="plan-soon">{{
                        copy.soon
                    }}</span>
                </span>
            </li>
        </ul>

        <Button
            v-if="offer.owned"
            size="pill"
            variant="outline"
            class="mt-auto w-full"
            disabled
        >
            <Check class="size-4" /> {{ copy.owned }}
        </Button>
        <Button
            v-else-if="offer.online"
            size="pill"
            class="mt-auto w-full"
            :variant="featured ? 'default' : 'outline'"
            :disabled="!canBuy || busy"
            @click="$emit('buy')"
        >
            <Spinner v-if="busy" />
            {{ upgrading ? copy.upgrade(offer.due) : copy.buy(offer.due) }}
        </Button>
        <CoutureRequest v-else class="mt-auto w-full" />
    </article>
</template>
