<script setup lang="ts">
import { X } from '@lucide/vue';
import { Input } from '@/components/ui/input';
import { eventTypes, programme } from '@/content/setup';
import type { ProgrammeRow } from '@/types/setup';

defineProps<{
    index: number;
}>();

defineEmits<{ remove: [] }>();

/* The row object lives in the shared setup form, so editing it in place is the intent. */
const row = defineModel<ProgrammeRow>('row', { required: true });
</script>

<!-- One part of the day as a timeline card: when, what, which day. -->
<template>
    <div class="programme-item">
        <div class="programme-time field">
            <label :for="`event_time_${index}`" class="field-label">{{
                programme.time
            }}</label>
            <Input
                :id="`event_time_${index}`"
                v-model="row.time"
                type="time"
                step="900"
                class="tabular-nums"
                required
            />
        </div>
        <div class="programme-what field">
            <label :for="`event_name_${index}`" class="field-label">{{
                eventTypes[row.type]
            }}</label>
            <Input
                :id="`event_name_${index}`"
                v-model="row.name"
                :placeholder="programme.customName"
                maxlength="80"
            />
        </div>
        <div class="programme-day field">
            <label :for="`event_day_${index}`" class="field-label">{{
                programme.day
            }}</label>
            <select
                :id="`event_day_${index}`"
                v-model.number="row.day_offset"
                class="select-native"
            >
                <option
                    v-for="(label, value) in programme.days"
                    :key="value"
                    :value="Number(value)"
                >
                    {{ label }}
                </option>
            </select>
        </div>
        <button
            type="button"
            class="programme-remove icon-button size-11"
            :aria-label="`${eventTypes[row.type]} ${programme.remove}`"
            @click="$emit('remove')"
        >
            <X class="size-4" />
        </button>
    </div>
</template>
