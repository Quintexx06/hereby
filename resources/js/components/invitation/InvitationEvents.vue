<script setup lang="ts">
import InvitationEventCard from '@/components/invitation/InvitationEventCard.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingEvent } from '@/types';

defineProps<{
    events: WeddingEvent[];
}>();

const { t } = useTrans();
</script>

<template>
    <section class="flex flex-col gap-4">
        <h2 class="eyebrow">{{ t('invitation.events_heading') }}</h2>
        <ol>
            <InvitationEventCard
                v-for="event in events"
                :key="event.id"
                :event="event"
            />
        </ol>
    </section>
</template>
