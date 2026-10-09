<script setup lang="ts">
import { Plus, X } from '@lucide/vue';
import { Input } from '@/components/ui/input';
import { contentPage as copy } from '@/content/content';

defineProps<{ idPrefix: string }>();
const items = defineModel<{ question: string; answer: string }[]>({
    required: true,
});
</script>

<template>
    <div class="flex flex-col gap-4">
        <div v-for="(item, index) in items" :key="index" class="faq-item-field">
            <div class="flex flex-1 flex-col gap-2">
                <label :for="`${idPrefix}-q-${index}`" class="sr-only">{{
                    copy.question
                }}</label>
                <Input
                    :id="`${idPrefix}-q-${index}`"
                    v-model="item.question"
                    maxlength="160"
                    :placeholder="copy.question"
                />
                <label :for="`${idPrefix}-a-${index}`" class="sr-only">{{
                    copy.answer
                }}</label>
                <textarea
                    :id="`${idPrefix}-a-${index}`"
                    v-model="item.answer"
                    rows="2"
                    maxlength="1000"
                    class="textarea min-h-20"
                    :placeholder="copy.answer"
                />
            </div>
            <button
                type="button"
                class="icon-button"
                :aria-label="copy.removeQuestion"
                @click="items.splice(index, 1)"
            >
                <X class="size-4" />
            </button>
        </div>
        <button
            v-if="items.length < 12"
            type="button"
            class="link-underline hit-area inline-flex items-center gap-1.5 self-start text-sm"
            @click="items.push({ question: '', answer: '' })"
        >
            <Plus class="size-4" /> {{ copy.addQuestion }}
        </button>
    </div>
</template>
