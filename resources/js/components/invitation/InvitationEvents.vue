<script setup lang="ts">
import InvitationEventCard from '@/components/invitation/InvitationEventCard.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingEvent } from '@/types';

/* Scrollable: Panorama's sideways row, which keyboard users must reach too. */
defineProps<{
    events: WeddingEvent[];
    scrollable?: boolean;
}>();

const { t } = useTrans();
</script>

<template>
    <section class="inv-section">
        <h2 class="inv-heading">
            {{ t('invitation.programme_heading') }}
        </h2>
        <ol
            class="event-timeline"
            :tabindex="scrollable ? 0 : undefined"
            :aria-label="
                scrollable ? t('invitation.programme_heading') : undefined
            "
        >
            <InvitationEventCard
                v-for="event in events"
                :key="event.id"
                :event="event"
            />
        </ol>
    </section>
</template>
