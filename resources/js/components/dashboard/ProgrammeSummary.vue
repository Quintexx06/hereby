<script setup lang="ts">
import { computed } from 'vue';
import { active } from '@/content/dashboard';
import { eventTypes } from '@/content/setup';
import { formatDay, formatTime } from '@/lib/format';
import type { OverviewEvent } from '@/types';

const props = defineProps<{ events: OverviewEvent[]; hasReplies: boolean }>();

/* On a one-day wedding the day is noise; show it only when the days differ. */
const severalDays = computed(
    () =>
        new Set(props.events.map((event) => event.starts_at.slice(0, 10)))
            .size > 1,
);
</script>

<!-- The parts of the day with who is invited and, once replies come, who said yes. -->
<template>
    <section aria-labelledby="programme" class="ledger-section">
        <h2 id="programme" class="ledger-title">
            {{ active.programme }}
        </h2>
        <ol class="ledger-rows">
            <li
                v-for="event in events"
                :key="event.id"
                class="grid grid-cols-[5rem_minmax(0,1fr)] gap-4"
            >
                <span class="flex flex-col text-sm">
                    <span
                        class="text-lg font-semibold text-brand tabular-nums"
                        >{{ formatTime(event.starts_at, 'de-CH') }}</span
                    >
                    <span v-if="severalDays" class="text-muted-foreground">{{
                        formatDay(event.starts_at, 'de-CH')
                    }}</span>
                </span>
                <span class="flex flex-col">
                    <span class="text-lg font-semibold">{{
                        event.name || eventTypes[event.type]
                    }}</span>
                    <span class="text-sm text-muted-foreground">
                        {{ active.invited(event.invited)
                        }}<template v-if="hasReplies"
                            >, {{ active.attending(event.attending) }}</template
                        >
                    </span>
                </span>
            </li>
        </ol>
    </section>
</template>
