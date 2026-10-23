<script setup lang="ts">
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import FaqBlock from '@/components/invitation/blocks/FaqBlock.vue';
import StayBlock from '@/components/invitation/blocks/StayBlock.vue';
import VenueBlock from '@/components/invitation/blocks/VenueBlock.vue';
import GartenStoryArches from '@/components/invitation/templates/garten/GartenStoryArches.vue';
import { useTrans } from '@/composables/useTrans';
import type { InvitationBlock } from '@/types';

defineProps<{ block: InvitationBlock }>();
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
        <GartenStoryArches v-if="block.type === 'story'" :block="block" />
        <VenueBlock v-else-if="block.type === 'venue'" :block="block" />
        <FaqBlock v-else-if="block.type === 'faq'" :block="block" />
        <StayBlock v-else-if="block.type === 'stay'" :block="block" />
        <div v-else class="inv-statement">
            <BlockText :text="block.body" />
        </div>
    </section>
</template>
