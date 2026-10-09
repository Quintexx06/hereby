<script setup lang="ts">
import { active } from '@/content/dashboard';
import { eventTypes } from '@/content/setup';
import { formatDay, formatTime } from '@/lib/format';
import type { OverviewEvent } from '@/types';

defineProps<{ events: OverviewEvent[] }>();
</script>

<!-- The parts of the day with who is invited and who has said yes. -->
<template>
    <section aria-labelledby="programme">
        <h2 id="programme" class="app-section-title mb-2">
            {{ active.programme }}
        </h2>
        <ol>
            <li
                v-for="event in events"
                :key="event.id"
                class="app-row grid-cols-[5.5rem_minmax(0,1fr)] sm:grid-cols-[7rem_minmax(0,1fr)_auto]"
            >
                <span class="flex flex-col text-sm">
                    <span class="font-semibold tabular-nums">{{
                        formatTime(event.starts_at, 'de-CH')
                    }}</span>
                    <span class="text-muted-foreground">{{
                        formatDay(event.starts_at, 'de-CH')
                    }}</span>
                </span>
                <span class="font-medium">{{
                    event.name || eventTypes[event.type]
                }}</span>
                <span
                    class="col-start-2 text-sm text-muted-foreground sm:col-start-auto sm:text-right"
                >
                    {{ active.invited(event.invited) }}
                    <span class="block">{{
                        active.attending(event.attending)
                    }}</span>
                </span>
            </li>
        </ol>
    </section>
</template>
