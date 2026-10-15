<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { CircleAlert, TriangleAlert } from '@lucide/vue';
import { computed } from 'vue';
import { adminLog as copy } from '@/content/log';
import { update } from '@/routes/admin/log';
import type { LogEntryRow } from '@/types';

const props = defineProps<{ entry: LogEntryRow }>();

const when = (iso: string) =>
    new Intl.DateTimeFormat('de-CH', {
        day: 'numeric',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit',
    }).format(new Date(iso));

const meta = computed(() =>
    [
        copy.times(props.entry.count),
        copy.lastSeen(when(props.entry.last_seen)),
        props.entry.count > 1
            ? copy.firstSeen(when(props.entry.first_seen))
            : null,
    ]
        .filter(Boolean)
        .join(' · '),
);

function toggle(): void {
    router.patch(update.url(props.entry.id), {}, { preserveScroll: true });
}
</script>

<!-- One problem: level, message, where it happened, how often, the trace. -->
<template>
    <li
        class="log-entry"
        :data-error="entry.is_error"
        :data-resolved="entry.resolved"
    >
        <component
            :is="entry.is_error ? CircleAlert : TriangleAlert"
            class="log-entry-icon"
            aria-hidden="true"
        />
        <div class="flex min-w-0 flex-col gap-1.5">
            <p class="log-entry-level">
                {{ copy.levels[entry.level] ?? entry.level }}
                <span
                    v-if="entry.exception"
                    class="font-normal text-muted-foreground"
                >
                    · {{ entry.exception.split('\\').pop() }}
                </span>
            </p>
            <p class="font-semibold break-words">{{ entry.message }}</p>
            <p class="caption break-all">
                {{ [entry.url, entry.location].filter(Boolean).join(' · ') }}
            </p>
            <p class="caption">{{ meta }}</p>
            <details v-if="entry.trace" class="log-entry-trace">
                <summary>{{ copy.trace }}</summary>
                <pre>{{ entry.trace }}</pre>
            </details>
        </div>
        <button type="button" class="witness-action self-start" @click="toggle">
            {{ entry.resolved ? copy.reopen : copy.resolve }}
        </button>
    </li>
</template>
