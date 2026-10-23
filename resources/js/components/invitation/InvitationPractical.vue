<script setup lang="ts">
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import StayBlock from '@/components/invitation/blocks/StayBlock.vue';
import VenueBlock from '@/components/invitation/blocks/VenueBlock.vue';
import { blockIcons } from '@/lib/invitation-icons';
import type { InvitationBlock } from '@/types';

/*
 * Level: the heading's rank where the design sets these blocks; icon: the
 * chapter's sign before the heading, for designs that draw one.
 */
const { level = 3, icon = false } = defineProps<{
    block: InvitationBlock;
    heading: string;
    level?: 2 | 3;
    icon?: boolean;
}>();
</script>

<!-- One practical chapter (place, travel, stay, dress code): heading, then its body. -->
<template>
    <section
        :id="`block-${block.id}`"
        class="inv-practical"
        :data-block="block.type"
        :aria-labelledby="`block-${block.id}-heading`"
    >
        <component
            :is="blockIcons[block.type]"
            v-if="icon && blockIcons[block.type]"
            class="inv-practical-icon"
            aria-hidden="true"
        />
        <component
            :is="`h${level}`"
            :id="`block-${block.id}-heading`"
            class="inv-practical-heading"
        >
            {{ heading }}
        </component>
        <VenueBlock v-if="block.type === 'venue'" :block="block" />
        <StayBlock v-else-if="block.type === 'stay'" :block="block" />
        <BlockText v-else :text="block.body" />
    </section>
</template>
