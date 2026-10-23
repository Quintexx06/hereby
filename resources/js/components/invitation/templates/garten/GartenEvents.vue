<script setup lang="ts">
import GartenEventCard from '@/components/invitation/templates/garten/GartenEventCard.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingEvent } from '@/types';

defineProps<{ events: WeddingEvent[] }>();

const { t } = useTrans();
</script>

<!-- Garten's day on a vine: one leaf for each part. -->
<template>
    <section
        id="inv-programme"
        class="inv-section"
        data-block="programme"
        data-layout="split"
    >
        <h2 class="inv-heading">
            {{ t('invitation.programme_heading') }}
        </h2>
        <ol class="event-timeline">
            <GartenEventCard
                v-for="event in events"
                :key="event.id"
                :event="event"
            />
        </ol>
    </section>
</template>
