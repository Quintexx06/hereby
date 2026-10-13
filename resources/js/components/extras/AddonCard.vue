<script setup lang="ts">
import { Check } from '@lucide/vue';
import { computed } from 'vue';
import AddonScene from '@/components/extras/AddonScene.vue';
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

<!-- One extra: its scene, what it does, and the price beside one way to get it. -->
<template>
    <article class="addon-card" :data-owned="offer.owned">
        <AddonScene :kind="offer.key" />
        <div class="flex flex-col gap-2">
            <h2 class="plan-name">{{ addon.name }}</h2>
            <p class="text-sm leading-relaxed text-muted-foreground">
                {{ addon.line }}
            </p>
        </div>

        <div class="addon-foot">
            <p class="plan-price text-3xl">
                {{ chf(offer.price) }}
                <span v-if="addon.perYear" class="text-sm font-medium"
                    >/ Jahr</span
                >
            </p>
            <Button
                v-if="offer.owned && !renewable"
                size="pill"
                variant="outline"
                disabled
            >
                <Check class="size-4" />
                {{ offer.included ? copy.included : copy.owned }}
            </Button>
            <Button
                v-else-if="offer.buyable"
                size="pill"
                :disabled="!canBuy || busy"
                @click="$emit('buy')"
            >
                <Spinner v-if="busy" />
                {{ offer.owned ? copy.renewShort : copy.buyShort }}
            </Button>
            <p v-else-if="hint" class="text-sm text-muted-foreground">
                {{ hint }}
            </p>
        </div>
    </article>
</template>
