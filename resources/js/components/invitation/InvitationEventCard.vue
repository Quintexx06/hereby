<script setup lang="ts">
import { useTrans } from '@/composables/useTrans';
import { formatDay, formatTime } from '@/lib/format';
import type { WeddingEvent } from '@/types';

defineProps<{
    event: WeddingEvent;
}>();

const { t, localeTag } = useTrans();
</script>

<template>
    <li class="event-card">
        <time :datetime="event.startsAt" class="flex flex-col">
            <span class="caption">{{
                formatDay(event.startsAt, localeTag())
            }}</span>
            <span class="event-time">{{
                formatTime(event.startsAt, localeTag())
            }}</span>
        </time>
        <div class="flex flex-col gap-1">
            <h3 class="title">
                {{ event.name ?? t(`invitation.event_types.${event.type}`) }}
            </h3>
            <p v-if="event.locationName" class="font-medium">
                {{ event.locationName }}
            </p>
            <p v-if="event.address" class="text-sm text-muted-foreground">
                {{ event.address }}
            </p>
        </div>
    </li>
</template>
