<script setup lang="ts">
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import FaqBlock from '@/components/invitation/blocks/FaqBlock.vue';
import VenueBlock from '@/components/invitation/blocks/VenueBlock.vue';
import { useTrans } from '@/composables/useTrans';
import type { InvitationBlock } from '@/types';

defineProps<{ blocks: InvitationBlock[] }>();
const { t } = useTrans();
</script>

<!-- The couple's own words, after the programme: story, venue, dress code, FAQ. -->
<template>
    <div v-if="blocks.length" class="flex flex-col gap-14 pt-16">
        <section
            v-for="block in blocks"
            :key="block.id"
            class="invitation-block"
            :aria-labelledby="`block-${block.id}`"
        >
            <h2 :id="`block-${block.id}`" class="title">
                {{ block.title ?? t(`invitation.blocks.${block.type}`) }}
            </h2>
            <VenueBlock v-if="block.type === 'venue'" :block="block" />
            <FaqBlock v-else-if="block.type === 'faq'" :block="block" />
            <BlockText v-else :text="block.body" />
        </section>
    </div>
</template>
