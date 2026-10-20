<script setup lang="ts">
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import { useTrans } from '@/composables/useTrans';
import type { InvitationBlock } from '@/types';

defineProps<{ block: InvitationBlock }>();
const { t } = useTrans();
</script>

<template>
    <div class="block-venue">
        <div v-if="block.venue" class="flex flex-col gap-1">
            <p v-if="block.venue.name" class="block-venue-name">
                {{ block.venue.name }}
            </p>
            <p v-if="block.venue.address" class="text-muted-foreground">
                {{ block.venue.address }}
            </p>
        </div>
        <BlockText :text="block.body" />
        <a
            v-if="block.venue?.route"
            :href="block.venue.route"
            target="_blank"
            rel="noopener noreferrer"
            class="block-route link-underline hit-area font-medium"
        >
            {{ t('invitation.route') }}
        </a>
    </div>
</template>
