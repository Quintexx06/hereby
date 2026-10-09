<script setup lang="ts">
import { useTrans } from '@/composables/useTrans';
import type { DraftNotes } from '@/types';

/*
 * Write-only: what is on file never comes back to the browser (CLAUDE.md
 * rule 9). The guest sees that something is stored and can replace or remove it.
 */
defineProps<{ id: string; name: string; onFile: boolean; disabled: boolean }>();
const model = defineModel<DraftNotes>({ required: true });

const { t } = useTrans();
</script>

<template>
    <div class="field">
        <label :for="id" class="field-label">{{
            t('rsvp.allergies_label', { name })
        }}</label>
        <input
            :id="id"
            v-model="model.notes"
            type="text"
            class="reply-input"
            maxlength="500"
            autocomplete="off"
            :placeholder="t('rsvp.allergies_placeholder')"
            :disabled="disabled || model.clear"
        />
        <p v-if="onFile" class="field-hint flex flex-wrap items-center gap-x-3">
            <span>{{ t('rsvp.allergies_on_file') }}</span>
            <label class="check-label">
                <input
                    v-model="model.clear"
                    type="checkbox"
                    class="checkbox"
                    :disabled="disabled"
                />
                {{ t('rsvp.allergies_remove') }}
            </label>
        </p>
    </div>
</template>
