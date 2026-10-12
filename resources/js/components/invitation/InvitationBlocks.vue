<script setup lang="ts">
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import FaqBlock from '@/components/invitation/blocks/FaqBlock.vue';
import StayBlock from '@/components/invitation/blocks/StayBlock.vue';
import StoryArches from '@/components/invitation/blocks/StoryArches.vue';
import StoryFilm from '@/components/invitation/blocks/StoryFilm.vue';
import StoryPath from '@/components/invitation/blocks/StoryPath.vue';
import VenueBlock from '@/components/invitation/blocks/VenueBlock.vue';
import InvitationOrnament from '@/components/invitation/InvitationOrnament.vue';
import { useTrans } from '@/composables/useTrans';
import type { InvitationBlock } from '@/types';

/* Each template tells the story its own way; the rest of the blocks are shared. */
const { story = 'path' } = defineProps<{
    blocks: InvitationBlock[];
    story?: 'path' | 'arches' | 'film';
}>();
const { t } = useTrans();
</script>

<!-- The couple's own words as chapters of the page, each with its own room. -->
<template>
    <template v-for="block in blocks" :key="block.id">
        <section
            class="inv-section"
            :data-block="block.type"
            :aria-labelledby="`block-${block.id}`"
        >
            <h2 :id="`block-${block.id}`" class="inv-heading">
                {{ block.title ?? t(`invitation.blocks.${block.type}`) }}
            </h2>
            <template v-if="block.type === 'story'">
                <StoryArches v-if="story === 'arches'" :block="block" />
                <StoryFilm v-else-if="story === 'film'" :block="block" />
                <StoryPath v-else :block="block" />
            </template>
            <VenueBlock v-else-if="block.type === 'venue'" :block="block" />
            <FaqBlock v-else-if="block.type === 'faq'" :block="block" />
            <StayBlock v-else-if="block.type === 'stay'" :block="block" />
            <div v-else class="inv-statement">
                <BlockText :text="block.body" />
            </div>
        </section>
        <InvitationOrnament v-if="block.type === 'story'" />
    </template>
</template>
