<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { contentPage as copy } from '@/content/content';
import { eventTypes } from '@/content/setup';
import type { ContentEvent } from '@/types';

/* Who sees a block: every guest, or only those invited to one part of the day. */
defineProps<{ id: string; events: ContentEvent[]; error?: string }>();
const eventId = defineModel<number | null>({ required: true });
</script>

<template>
    <div class="field">
        <label :for="id" class="field-label">{{ copy.visibility }}</label>
        <select :id="id" v-model="eventId" class="select-native sm:max-w-sm">
            <option :value="null">{{ copy.everyone }}</option>
            <option v-for="event in events" :key="event.id" :value="event.id">
                {{ copy.onlyEvent(event.name || eventTypes[event.type]) }}
            </option>
        </select>
        <InputError :message="error" />
    </div>
</template>
