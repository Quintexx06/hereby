<script setup lang="ts">
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import FaqBlock from '@/components/invitation/blocks/FaqBlock.vue';
import StayBlock from '@/components/invitation/blocks/StayBlock.vue';
import StoryArches from '@/components/invitation/blocks/StoryArches.vue';
import StoryFilm from '@/components/invitation/blocks/StoryFilm.vue';
import StoryPath from '@/components/invitation/blocks/StoryPath.vue';
import VenueBlock from '@/components/invitation/blocks/VenueBlock.vue';
import { useTrans } from '@/composables/useTrans';
import type { InvitationBlock } from '@/types';

defineProps<{ block: InvitationBlock; story: 'path' | 'arches' | 'film' }>();
const { t } = useTrans();
</script>

<!-- One of the couple's chapters: its heading, then its own layout. -->
<template>
    <section
        class="inv-section"
        :data-block="block.type"
        :data-layout="block.type === 'story' ? 'full' : 'split'"
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
</template>
