<script setup lang="ts">
import { kitchenPage as copy } from '@/content/kitchen';
import { eventTypes } from '@/content/setup';
import { formatDay, formatTime } from '@/lib/format';
import type { KitchenEvent } from '@/types';

defineProps<{ event: KitchenEvent }>();
</script>

<!-- One part of the day: name and time left, the head count right, menus below. -->
<template>
    <li class="kitchen-row">
        <div class="flex items-end justify-between gap-6">
            <div class="flex min-w-0 flex-col gap-1">
                <h3
                    class="font-display text-xl font-semibold tracking-[-0.02em]"
                >
                    {{ event.name || eventTypes[event.type] }}
                </h3>
                <p class="text-sm text-muted-foreground">
                    {{ formatDay(event.starts_at, 'de-CH') }},
                    {{ formatTime(event.starts_at, 'de-CH') }}
                </p>
                <p class="text-sm text-muted-foreground">
                    {{ copy.children(event.children)
                    }}<template v-if="event.pending"
                        >, {{ copy.pending(event.pending) }}</template
                    >
                </p>
            </div>
            <p class="flex shrink-0 items-baseline gap-2">
                <span class="kitchen-number">{{ event.attending }}</span>
                <span class="text-sm text-muted-foreground">{{
                    copy.people(event.attending)
                }}</span>
            </p>
        </div>
        <ul v-if="event.menus.length" class="kitchen-menus">
            <li v-for="menu in event.menus" :key="menu.label">
                <span>{{ menu.label }}</span>
                <span class="font-semibold tabular-nums">{{ menu.count }}</span>
            </li>
        </ul>
    </li>
</template>
