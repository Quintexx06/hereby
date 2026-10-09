<script setup lang="ts">
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import { useTrans } from '@/composables/useTrans';
import type { InvitationBlock } from '@/types';

defineProps<{ block: InvitationBlock }>();
const { t } = useTrans();
</script>

<!-- Hotels the couple recommends, each with its booking details and a link. -->
<template>
    <div class="flex flex-col gap-5">
        <BlockText :text="block.body" />
        <ul class="flex flex-col">
            <li
                v-for="(hotel, index) in block.items"
                :key="index"
                class="block-stay"
            >
                <p class="font-semibold">{{ hotel.question }}</p>
                <p v-if="hotel.answer" class="text-muted-foreground">
                    {{ hotel.answer }}
                </p>
                <a
                    v-if="hotel.url"
                    :href="hotel.url"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="link-underline hit-area self-start text-sm font-medium"
                    >{{ t('invitation.hotel_link') }}</a
                >
            </li>
        </ul>
    </div>
</template>
