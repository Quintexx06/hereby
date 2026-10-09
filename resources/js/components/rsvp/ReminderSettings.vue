<script setup lang="ts">
import { computed } from 'vue';
import SwitchRow from '@/components/rsvp/SwitchRow.vue';
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
    <div class="flex flex-col gap-3">
        <SwitchRow
            v-model="enabled"
            :label="copy.remindersToggle"
            :hint="copy.remindersHint"
        />
        <p v-if="enabled" class="text-sm font-medium">
            {{
                dates.length
                    ? copy.remindersNext(upcoming)
                    : copy.remindersNoDeadline
            }}
        </p>
    </div>
</template>
