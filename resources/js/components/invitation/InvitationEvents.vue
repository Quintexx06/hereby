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
    <section class="invitation-section">
        <h2 class="invitation-heading">
            {{ t('invitation.programme_heading') }}
        </h2>
        <ol class="event-timeline">
            <InvitationEventCard
                v-for="event in events"
                :key="event.id"
                :event="event"
            />
        </ol>
    </section>
</template>
