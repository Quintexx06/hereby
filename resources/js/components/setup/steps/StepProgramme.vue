<script setup lang="ts">
import { computed } from 'vue';
import ProgrammeRowField from '@/components/setup/ProgrammeRowField.vue';
import InputError from '@/components/InputError.vue';
import { celebrations, eventTypes, programme } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import { sortProgramme } from '@/lib/setup';
import type { Celebration, ProgrammeRow } from '@/types/setup';
import type { EventType } from '@/types/wedding';

const props = defineProps<{
    presets: Record<Celebration, ProgrammeRow[]> | null;
}>();

const form = useSetupForm();

/* Choosing day or evening fills in a sensible programme to adjust. */
function chooseCelebration(value: Celebration): void {
    form.celebration = value;
    form.events = (props.presets?.[value] ?? []).map((row) => ({
        ...row,
        name: null,
    }));
}

const missing = computed(() =>
    (Object.keys(eventTypes) as EventType[]).filter(
        (type) =>
            type === 'other' ||
            !form.events.some((event) => event.type === type),
    ),
);

function add(type: EventType): void {
    form.events = sortProgramme([
        ...form.events,
        {
            type,
            time:
                type === 'civil_ceremony'
                    ? '10:00'
                    : type === 'brunch'
                      ? '11:00'
                      : '12:00',
            day_offset:
                type === 'civil_ceremony' ? -1 : type === 'brunch' ? 1 : 0,
            name: null,
        },
    ]);
}
</script>

<template>
    <div class="flex flex-col gap-10">
        <fieldset class="grid gap-3">
            <legend class="field-label mb-3">Wann feiert ihr?</legend>
            <label
                v-for="(option, value) in celebrations"
                :key="value"
                class="choice"
            >
                <input
                    type="radio"
                    name="celebration"
                    class="sr-only"
                    :value="value"
                    :checked="form.celebration === value"
                    @change="chooseCelebration(value)"
                />
                <span class="choice-mark" aria-hidden="true" />
                <span class="flex flex-col gap-0.5">
                    <span class="font-medium">{{ option.label }}</span>
                    <span class="text-sm text-muted-foreground">{{
                        option.hint
                    }}</span>
                </span>
            </label>
            <InputError :message="form.errors.celebration" />
        </fieldset>

        <div v-if="form.events.length" class="flex flex-col">
            <ProgrammeRowField
                v-for="(row, index) in form.events"
                :key="`${index}-${row.type}`"
                v-model:row="form.events[index]"
                :index="index"
                @remove="form.events.splice(index, 1)"
            />
            <div class="flex flex-wrap items-center gap-2 border-t pt-4">
                <span class="mr-2 text-sm text-muted-foreground"
                    >{{ programme.addPart }}:</span
                >
                <button
                    v-for="type in missing"
                    :key="type"
                    type="button"
                    class="chip"
                    @click="add(type)"
                >
                    {{ eventTypes[type] }}
                </button>
            </div>
            <InputError class="mt-2" :message="form.errors.events" />
        </div>
    </div>
</template>
