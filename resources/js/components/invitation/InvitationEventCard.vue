<script setup lang="ts">
import {
    Coffee,
    Gem,
    Landmark,
    Music,
    Sparkles,
    UtensilsCrossed,
    Wine,
} from '@lucide/vue';
import type { LucideIcon } from '@lucide/vue';
import { computed } from 'vue';
import { useTrans } from '@/composables/useTrans';
import { formatDay, formatTime } from '@/lib/format';
import type { EventType, WeddingEvent } from '@/types';

const props = defineProps<{
    event: WeddingEvent;
}>();

const { t, localeTag } = useTrans();

const icons: Record<EventType, LucideIcon> = {
    civil_ceremony: Landmark,
    ceremony: Gem,
    reception: Wine,
    dinner: UtensilsCrossed,
    party: Music,
    brunch: Coffee,
    other: Sparkles,
};

/* A plain maps link: nothing is requested until the guest taps it. */
const route = computed(() => {
    const place = [props.event.locationName, props.event.address]
        .filter(Boolean)
        .join(', ');

    return place
        ? `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place)}`
        : null;
});
</script>

<!-- One stop on the day's timeline: icon, time, what, where. -->
<template>
    <li class="event-card">
        <span class="event-node" aria-hidden="true">
            <component :is="icons[event.type]" class="size-4" />
        </span>
        <time :datetime="event.startsAt" class="flex flex-col">
            <span class="event-time">{{
                formatTime(event.startsAt, localeTag())
            }}</span>
            <span class="caption">{{
                formatDay(event.startsAt, localeTag())
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
            <a
                v-if="route"
                :href="route"
                target="_blank"
                rel="noopener noreferrer"
                class="link-underline hit-area mt-1 self-start text-sm font-medium"
                >{{ t('invitation.route') }}</a
            >
        </div>
    </li>
</template>
