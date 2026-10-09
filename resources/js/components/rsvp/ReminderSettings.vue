<script setup lang="ts">
import { computed } from 'vue';
import { rsvpSettings as copy } from '@/content/rsvp';
import { formatList } from '@/lib/format';

const props = defineProps<{ dates: string[] }>();
const enabled = defineModel<boolean>({ required: true });

const upcoming = computed(() =>
    formatList(
        props.dates.map((date) =>
            new Intl.DateTimeFormat('de-CH', {
                day: 'numeric',
                month: 'long',
            }).format(new Date(date)),
        ),
        'de-CH',
    ),
);
</script>

<!-- Reminders run by themselves; the couple sees when (roadmap 1.10). -->
<template>
    <section class="settings-section">
        <h2 class="ledger-title">{{ copy.reminders }}</h2>
        <label class="check-label">
            <input v-model="enabled" type="checkbox" class="checkbox" />
            {{ copy.remindersToggle }}
        </label>
        <p class="field-hint max-w-prose">{{ copy.remindersHint }}</p>
        <p v-if="enabled" class="text-sm font-medium">
            {{
                dates.length
                    ? copy.remindersNext(upcoming)
                    : copy.remindersNoDeadline
            }}
        </p>
    </section>
</template>
