<script setup lang="ts">
import type { DateValue } from '@internationalized/date';
import { getLocalTimeZone, parseDate, today } from '@internationalized/date';
import { CalendarDays, ChevronLeft, ChevronRight } from '@lucide/vue';
import {
    CalendarCell,
    CalendarCellTrigger,
    CalendarGrid,
    CalendarGridBody,
    CalendarGridHead,
    CalendarGridRow,
    CalendarHeadCell,
    CalendarHeader,
    CalendarNext,
    CalendarPrev,
    CalendarRoot,
    PopoverContent,
    PopoverPortal,
    PopoverRoot,
    PopoverTrigger,
} from 'reka-ui';
import { computed, ref, shallowRef, watch } from 'vue';
import DateMonthSelect from './DateMonthSelect.vue';

const props = withDefaults(
    defineProps<{
        id?: string;
        /** Sent with a plain form (a hidden input carries the value). */
        name?: string;
        placeholder?: string;
        /** Earliest and latest day, as YYYY-MM-DD. */
        min?: string | null;
        max?: string | null;
        /** `field` looks like an input; `inline` is underlined text in a sentence. */
        variant?: 'field' | 'inline';
        ariaLabel?: string;
    }>(),
    {
        placeholder: 'Datum wählen',
        variant: 'field',
        min: null,
        max: null,
    },
);

/** The day as YYYY-MM-DD, or empty. */
const model = defineModel<string | null>({ default: '' });
const open = ref(false);

const toDate = (value: string | null | undefined): DateValue | undefined => {
    try {
        return value ? parseDate(value) : undefined;
    } catch {
        return undefined;
    }
};

const value = computed({
    get: () => toDate(model.value),
    set: (day: DateValue | undefined) => {
        model.value = day ? day.toString() : '';
        open.value = false;
    },
});

/** The month on show: the chosen day's, else the first allowed one, else this one. */
const shownMonth = shallowRef<DateValue>(today(getLocalTimeZone()));
watch(open, (isOpen) => {
    if (isOpen) {
        const earliest = toDate(props.min);
        const now = today(getLocalTimeZone());
        shownMonth.value =
            value.value ?? (earliest && earliest.compare(now) > 0 ? earliest : now);
    }
});

const label = computed(() =>
    value.value
        ? new Intl.DateTimeFormat('de-CH', { dateStyle: 'medium' }).format(
              value.value.toDate(getLocalTimeZone()),
          )
        : props.placeholder,
);
</script>

<!--
    A date picker in the shadcn manner: a calm trigger, a month that opens in
    a popover, Monday first, Swiss format. Keyboard works as in reka-ui.
-->
<template>
    <PopoverRoot v-model:open="open">
        <PopoverTrigger
            :id="id"
            type="button"
            class="date-trigger"
            :data-variant="variant"
            :data-empty="!value || undefined"
            :aria-label="ariaLabel"
        >
            <span class="truncate">{{ label }}</span>
            <CalendarDays class="date-trigger-icon" aria-hidden="true" />
        </PopoverTrigger>
        <input v-if="name" type="hidden" :name="name" :value="model ?? ''" />

        <PopoverPortal>
            <PopoverContent
                class="date-popover"
                align="start"
                :side-offset="8"
                :collision-padding="12"
            >
                <CalendarRoot
                    v-slot="{ grid, weekDays }"
                    v-model="value"
                    v-model:placeholder="shownMonth"
                    :min-value="toDate(min)"
                    :max-value="toDate(max)"
                    locale="de-CH"
                    :week-starts-on="1"
                    weekday-format="short"
                    fixed-weeks
                    class="date-calendar"
                >
                    <CalendarHeader class="date-header">
                        <CalendarPrev class="date-nav" aria-label="Vorheriger Monat">
                            <ChevronLeft class="size-4" />
                        </CalendarPrev>
                        <DateMonthSelect
                            v-model="shownMonth"
                            :min="toDate(min)"
                            :max="toDate(max)"
                        />
                        <CalendarNext class="date-nav" aria-label="Nächster Monat">
                            <ChevronRight class="size-4" />
                        </CalendarNext>
                    </CalendarHeader>

                    <CalendarGrid
                        v-for="month in grid"
                        :key="month.value.toString()"
                        class="date-grid"
                    >
                        <CalendarGridHead>
                            <CalendarGridRow class="date-row">
                                <CalendarHeadCell
                                    v-for="day in weekDays"
                                    :key="day"
                                    class="date-weekday"
                                >
                                    {{ day }}
                                </CalendarHeadCell>
                            </CalendarGridRow>
                        </CalendarGridHead>
                        <CalendarGridBody>
                            <CalendarGridRow
                                v-for="(week, index) in month.rows"
                                :key="index"
                                class="date-row"
                            >
                                <CalendarCell
                                    v-for="day in week"
                                    :key="day.toString()"
                                    :date="day"
                                    class="date-cell"
                                >
                                    <CalendarCellTrigger
                                        :day="day"
                                        :month="month.value"
                                        class="date-day"
                                    />
                                </CalendarCell>
                            </CalendarGridRow>
                        </CalendarGridBody>
                    </CalendarGrid>
                </CalendarRoot>
            </PopoverContent>
        </PopoverPortal>
    </PopoverRoot>
</template>
