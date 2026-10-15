<script setup lang="ts">
import { DatePicker } from '@/components/ui/date-picker';
import type { ShowcaseCouple } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';

defineProps<{ placeholder: ShowcaseCouple }>();

const first = defineModel<string>('first', { required: true });
const second = defineModel<string>('second', { required: true });
const date = defineModel<string>('date', { required: true });
</script>

<!-- One casual sentence with blanks: the example site takes the couple's names. -->
<template>
    <form class="couple-form" @submit.prevent>
        <span class="text-muted-foreground">{{ studio.tryLead }}</span>
        <span class="inline-flex flex-wrap items-baseline gap-x-2 gap-y-1">
            <input
                v-model="first"
                class="couple-field"
                :placeholder="placeholder.first"
                :aria-label="studio.firstLabel"
                maxlength="20"
                autocomplete="off"
                spellcheck="false"
            />
            <span class="text-brand">&amp;</span>
            <input
                v-model="second"
                class="couple-field"
                :placeholder="placeholder.second"
                :aria-label="studio.secondLabel"
                maxlength="20"
                autocomplete="off"
                spellcheck="false"
            />
            <span class="text-muted-foreground">{{ studio.tryJoin }}</span>
            <DatePicker
                v-model="date"
                variant="inline"
                :aria-label="studio.dateLabel"
            />
        </span>
    </form>
</template>
