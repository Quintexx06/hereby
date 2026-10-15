<script setup lang="ts">
import { DatePicker } from '@/components/ui/date-picker';
import { computed, ref, watch } from 'vue';
import InputError from '@/components/InputError.vue';
import { date } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import { formatDate } from '@/lib/format';
import { daysUntil, suggestedDeadline } from '@/lib/setup';

const form = useSetupForm();
const today = new Date().toISOString().slice(0, 10);

/* The deadline follows the date until the couple sets it themselves. */
const deadlineTouched = ref(Boolean(form.rsvp_deadline));

watch(
    () => form.wedding_date,
    (value) => {
        if (value && !deadlineTouched.value) {
            form.rsvp_deadline = suggestedDeadline(value);
        }
    },
);

const summary = computed(() => {
    if (!form.wedding_date) {
        return null;
    }

    const weekday = new Intl.DateTimeFormat('de-CH', {
        weekday: 'long',
    }).format(new Date(`${form.wedding_date}T12:00:00`));

    return `${weekday}, ${formatDate(form.wedding_date, 'de-CH')}, ${date.daysLeft(daysUntil(form.wedding_date))}`;
});
</script>

<template>
    <div class="flex flex-col gap-8">
        <div class="field">
            <label for="wedding_date" class="field-label">{{
                date.wedding
            }}</label>
            <DatePicker
                id="wedding_date"
                v-model="form.wedding_date"
                :min="today"
                class="date-trigger-lg"
            />
            <p v-if="summary" class="field-hint" aria-live="polite">
                {{ summary }}
            </p>
            <InputError :message="form.errors.wedding_date" />
        </div>

        <div class="field">
            <label for="rsvp_deadline" class="field-label">{{
                date.deadline
            }}</label>
            <DatePicker
                id="rsvp_deadline"
                v-model="form.rsvp_deadline"
                :min="today"
                :max="form.wedding_date"
                @update:model-value="deadlineTouched = true"
            />
            <p class="field-hint">{{ date.deadlineHint }}</p>
            <InputError :message="form.errors.rsvp_deadline" />
        </div>
    </div>
</template>
