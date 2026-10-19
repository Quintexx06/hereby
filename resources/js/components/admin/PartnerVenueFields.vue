<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { adminPartners as copy } from '@/content/partners';

defineProps<{ noteError?: string }>();
const showcase = defineModel<boolean>('showcase', { required: true });
const note = defineModel<string>('note', { required: true });
</script>

<!-- What only venues have: Showcase, and the team's sentence for the pitch. -->
<template>
    <div class="partner-edit-wide grid gap-4">
        <div class="grid gap-2">
            <label for="edit-pitch-note" class="field-label">{{
                copy.pitchNote
            }}</label>
            <textarea
                id="edit-pitch-note"
                v-model="note"
                rows="2"
                maxlength="500"
                class="textarea min-h-20"
            />
            <p class="field-hint">{{ copy.pitchNoteHint }}</p>
            <InputError :message="noteError" />
        </div>
        <label class="flex items-start gap-3">
            <Checkbox
                :model-value="showcase"
                class="mt-0.5"
                @update:model-value="showcase = $event === true"
            />
            <span>
                <span class="font-semibold">{{ copy.showcase }}</span>
                <span class="field-hint block">{{ copy.showcaseHint }}</span>
            </span>
        </label>
    </div>
</template>
