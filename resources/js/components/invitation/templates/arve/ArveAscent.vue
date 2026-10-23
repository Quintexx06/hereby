<script setup lang="ts">
import { MountainSnow } from '@lucide/vue';
import { computed } from 'vue';
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import type { InvitationBlock } from '@/types';

const props = defineProps<{ block: InvitationBlock; summit: string }>();

/*
 * The chapters as stations on the way up and the wedding as the summit:
 * each station a little higher than the one before (in % of the climb).
 */
const stations = computed(() => {
    const count = props.block.items.length + 1;

    return Array.from({ length: count }, (_, index) => ({
        x: ((index + 0.5) / count) * 100,
        rise: count > 1 ? (index / (count - 1)) * 100 : 100,
    }));
});
const ridge = computed(() =>
    stations.value
        .map(({ x, rise }, index) => {
            const y = 100 - rise;

            return index === 0
                ? `M0 100 L${x} ${y}`
                : `L${x - 4} ${y + 9} L${x} ${y}`;
        })
        .join(' ')
        .concat(' L100 0'),
);
</script>

<!--
    «Unsere Geschichte» as an ascent: the couple's chapters climb along a
    ridge, one station higher each, to the summit, which is the wedding.
-->
<template>
    <div class="arve-ascent">
        <div v-if="block.body" class="inv-story-lede">
            <BlockText :text="block.body" />
        </div>
        <div class="arve-climb" :style="{ '--stations': stations.length }">
            <svg
                class="arve-ridge"
                viewBox="0 0 100 100"
                preserveAspectRatio="none"
                aria-hidden="true"
            >
                <path :d="ridge" pathLength="1" />
            </svg>
            <ol class="arve-stations">
                <li
                    v-for="(item, index) in block.items"
                    :key="index"
                    class="arve-station"
                    :style="{ '--rise': stations[index].rise }"
                >
                    <h3 class="arve-station-when">{{ item.question }}</h3>
                    <p class="arve-station-what">{{ item.answer }}</p>
                </li>
                <li class="arve-station arve-summit" :style="{ '--rise': 100 }">
                    <MountainSnow class="arve-summit-icon" aria-hidden="true" />
                    <p class="arve-station-when">{{ summit }}</p>
                </li>
            </ol>
        </div>
    </div>
</template>
