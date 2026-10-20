<script setup lang="ts">
import { computed } from 'vue';
import InvitationBlockSection from '@/components/invitation/InvitationBlockSection.vue';
import InvitationOrnament from '@/components/invitation/InvitationOrnament.vue';
import type { InvitationBlock } from '@/types';

/* Each template tells the story its own way; the rest of the blocks are shared. */
const { blocks, story = 'path' } = defineProps<{
    blocks: InvitationBlock[];
    story?: 'path' | 'arches' | 'film';
}>();

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
            <InvitationBlockSection
                v-for="block in group"
                :key="block.id"
                :block="block"
                :story="story"
            />
        </div>
        <InvitationBlockSection v-else :block="group[0]" :story="story" />
        <InvitationOrnament v-if="group[0].type === 'story'" />
    </template>
</template>
