<script setup lang="ts">
import FaqItemsField from '@/components/content/FaqItemsField.vue';
import { Input } from '@/components/ui/input';
import { blockTypes, contentPage as copy } from '@/content/content';
import type { BlockText, ContentBlockType } from '@/types';

/* One language's text for one block. */
defineProps<{ type: ContentBlockType; idPrefix: string }>();
const text = defineModel<BlockText>({ required: true });
</script>

<template>
    <div class="flex flex-col gap-5">
        <div class="field">
            <label :for="`${idPrefix}-title`" class="field-label">{{
                copy.customTitle
            }}</label>
            <Input
                :id="`${idPrefix}-title`"
                v-model="text.title"
                maxlength="80"
                :placeholder="blockTypes[type].label"
            />
        </div>
        <div v-if="type !== 'faq'" class="field">
            <label :for="`${idPrefix}-body`" class="field-label">{{
                copy.body
            }}</label>
            <textarea
                :id="`${idPrefix}-body`"
                v-model="text.body"
                rows="5"
                maxlength="3000"
                class="textarea"
                :placeholder="blockTypes[type].placeholder"
            />
            <p class="field-hint">{{ copy.bodyHint }}</p>
        </div>
        <FaqItemsField
            v-if="type === 'faq' || type === 'stay'"
            v-model="text.items"
            :id-prefix="idPrefix"
            :kind="type"
        />
    </div>
</template>
