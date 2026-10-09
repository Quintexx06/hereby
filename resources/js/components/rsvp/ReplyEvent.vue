<script setup lang="ts">
import PersonAnswer from '@/components/rsvp/PersonAnswer.vue';
import { useReplyForm } from '@/composables/useReplyForm';
import { useTrans } from '@/composables/useTrans';
import { formatDay, formatTime } from '@/lib/format';
import type { WeddingEvent } from '@/types';

defineProps<{ event: WeddingEvent }>();

const { t, localeTag } = useTrans();
const { reply } = useReplyForm();
</script>

<template>
    <section class="reply-event" :aria-labelledby="`event-${event.id}`">
        <header class="flex items-baseline justify-between gap-4">
            <h2 :id="`event-${event.id}`" class="title">
                {{ event.name ?? t(`invitation.event_types.${event.type}`) }}
            </h2>
            <p class="shrink-0 text-sm font-medium text-brand tabular-nums">
                {{ formatDay(event.startsAt, localeTag()) }},
                {{ formatTime(event.startsAt, localeTag()) }}
            </p>
        </header>
        <PersonAnswer
            v-for="guest in reply.guests"
            :key="guest.id"
            :guest="guest"
            :event="event"
        />
    </section>
</template>
