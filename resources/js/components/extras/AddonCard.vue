<script setup lang="ts">
import { Check } from '@lucide/vue';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { addons, chf, extrasPage as copy } from '@/content/extras';
import type { AddonOffer } from '@/content/extras';

const props = defineProps<{
    offer: AddonOffer;
    canBuy: boolean;
    busy: boolean;
    hasPlan: boolean;
}>();

defineEmits<{ buy: [] }>();

const addon = computed(() => addons[props.offer.key]);
/* Bought per year: it stays on offer after the first purchase. */
const renewable = computed(() => addon.value.perYear && props.offer.buyable);
const hint = computed(() => {
    if (!props.hasPlan) {
        return copy.needsPlan;
    }

    return props.offer.key === 'keep_memories' && !props.offer.buyable
        ? copy.needsAtelier
        : null;
});
</script>

<!-- One extra: name, price, what it does, one way to get it. -->
<template>
    <article class="plan" :data-owned="offer.owned">
        <h2 class="plan-name">{{ addon.name }}</h2>
        <p class="plan-price">
            {{ chf(offer.price) }}
            <span v-if="addon.perYear" class="text-base font-medium"
                >/ Jahr</span
            >
        </p>
        <p class="text-sm leading-relaxed text-muted-foreground">
            {{ addon.line }}
        </p>

        <Button
            v-if="offer.owned && !renewable"
            size="pill"
            variant="outline"
            class="mt-auto w-full"
            disabled
        >
            <Check class="size-4" />
            {{ offer.included ? copy.included : copy.owned }}
        </Button>
        <Button
            v-else-if="offer.buyable"
            size="pill"
            variant="outline"
            class="mt-auto w-full"
            :disabled="!canBuy || busy"
            @click="$emit('buy')"
        >
            <Spinner v-if="busy" />
            {{ offer.owned ? copy.renew(offer.price) : copy.buy(offer.price) }}
        </Button>
        <p v-else-if="hint" class="mt-auto text-sm text-muted-foreground">
            {{ hint }}
        </p>
    </article>
</template>
