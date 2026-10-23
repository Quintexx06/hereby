<script setup lang="ts">
import { computed } from 'vue';
import GartenBlockSection from '@/components/invitation/templates/garten/GartenBlockSection.vue';
import GartenOrnament from '@/components/invitation/templates/garten/GartenOrnament.vue';
import type { InvitationBlock } from '@/types';

const { blocks } = defineProps<{ blocks: InvitationBlock[] }>();

/*
 * The practical chapters (place, travel, stay, dress code) that follow each
 * other are kept together, so a wide screen can set them side by side.
 */
const practical = new Set(['venue', 'travel', 'stay', 'dress_code']);
const groups = computed(() =>
    blocks.reduce<InvitationBlock[][]>((all, block) => {
        const last = all.at(-1);

        if (last && practical.has(block.type) && practical.has(last[0].type)) {
            last.push(block);
        } else {
            all.push([block]);
        }

        return all;
    }, []),
);
</script>

<!-- The couple's own words as chapters of the page, each with its own room. -->
<template>
    <template v-for="group in groups" :key="group[0].id">
        <div v-if="group.length > 1" class="inv-practical">
            <GartenBlockSection
                v-for="block in group"
                :key="block.id"
                :block="block"
            />
        </div>
        <GartenBlockSection v-else :block="group[0]" />
        <GartenOrnament v-if="group[0].type === 'story'" />
    </template>
</template>
