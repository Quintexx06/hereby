<script setup lang="ts">
import type { DateValue } from '@internationalized/date';
import {
    getLocalTimeZone,
    parseDate,
    startOfMonth,
    today,
} from '@internationalized/date';
import { ChevronDown } from '@lucide/vue';
import { computed } from 'vue';

const props = defineProps<{ min?: DateValue; max?: DateValue }>();

/** The month the calendar shows (reka-ui's placeholder). */
const month = defineModel<DateValue>({ required: true });

const format = new Intl.DateTimeFormat('de-CH', {
    month: 'long',
    year: 'numeric',
});

const key = (day: DateValue): string => day.toString().slice(0, 7);

/** From the earliest allowed month (or this one) to the latest, three years at most by default. */
const months = computed(() => {
    const shown = startOfMonth(month.value);
    const start = startOfMonth(props.min ?? today(getLocalTimeZone()));
    const end = startOfMonth(props.max ?? start.add({ years: 3 }));
    const first = shown.compare(start) < 0 ? shown : start;
    const last = shown.compare(end) > 0 ? shown : end;
    const list: { key: string; label: string }[] = [];

    for (
        let current = first;
        current.compare(last) <= 0 && list.length < 120;
        current = current.add({ months: 1 })
    ) {
        list.push({
            key: key(current),
            label: format.format(current.toDate(getLocalTimeZone())),
        });
    }

    return list;
});

function pick(event: Event): void {
    month.value = parseDate(`${(event.target as HTMLSelectElement).value}-01`);
}
</script>

<!-- The month heading doubles as a menu: a date a year away is two taps, not twelve. -->
<template>
    <span class="date-month">
        <select
            :value="key(month)"
            class="date-month-select"
            aria-label="Monat wählen"
            @change="pick"
        >
            <option v-for="option in months" :key="option.key" :value="option.key">
                {{ option.label }}
            </option>
        </select>
        <ChevronDown class="date-month-icon" aria-hidden="true" />
    </span>
</template>
