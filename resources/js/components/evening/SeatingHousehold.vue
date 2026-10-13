<script setup lang="ts">
import { seatingPage as copy } from '@/content/evening';

export type SeatRow = {
    id: number;
    first_name: string;
    last_name: string | null;
    table_name: string;
    table_note: string;
};

defineProps<{ name: string; tablesListId: string }>();
const guests = defineModel<SeatRow[]>({ required: true });
</script>

<!-- One household's people: a table and a line about each person. -->
<template>
    <fieldset class="seating-household">
        <legend class="seating-household-name">{{ name }}</legend>
        <div v-for="guest in guests" :key="guest.id" class="seating-row">
            <span class="font-medium">
                {{ guest.first_name }} {{ guest.last_name }}
            </span>
            <label class="seating-field seating-field-table">
                <span class="sr-only">{{ copy.table }}</span>
                <input
                    v-model="guest.table_name"
                    :list="tablesListId"
                    :placeholder="copy.tablePlaceholder"
                    maxlength="40"
                    class="seating-input"
                />
            </label>
            <label class="seating-field">
                <span class="sr-only">{{ copy.note }}</span>
                <input
                    v-model="guest.table_note"
                    :placeholder="copy.notePlaceholder"
                    maxlength="160"
                    class="seating-input"
                />
            </label>
        </div>
    </fieldset>
</template>
