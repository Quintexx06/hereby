<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { editCopy } from '@/content/guests';
import { eventTypes } from '@/content/setup';
import { formatDay, formatTime } from '@/lib/format';
import type { GuestEvent } from '@/types';

defineProps<{ events: GuestEvent[]; error?: string }>();
const selected = defineModel<number[]>({ required: true });
</script>

<template>
    <fieldset class="editor-section">
        <legend class="sr-only">{{ editCopy.events }}</legend>
        <div class="flex flex-col gap-1" aria-hidden="true">
            <p class="field-label">{{ editCopy.events }}</p>
            <p class="field-hint">{{ editCopy.eventsHint }}</p>
        </div>
        <div class="grid gap-2">
            <label v-for="event in events" :key="event.id" class="event-choice">
                <input
                    v-model="selected"
                    type="checkbox"
                    :value="event.id"
                    class="checkbox"
                />
                <span class="font-medium">{{
                    event.name ?? eventTypes[event.type]
                }}</span>
                <span class="ml-auto text-muted-foreground tabular-nums">
                    {{ formatDay(event.starts_at, 'de-CH') }},
                    {{ formatTime(event.starts_at, 'de-CH') }}
                </span>
            </label>
        </div>
        <InputError :message="error" />
    </fieldset>
</template>
