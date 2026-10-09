<script setup lang="ts">
import { Plus, X } from '@lucide/vue';
import { computed } from 'vue';
import { Input } from '@/components/ui/input';
import { itemLabels } from '@/content/content';

/* List entries: FAQ question/answer pairs, or hotels with an optional link. */
const props = defineProps<{ idPrefix: string; kind: 'faq' | 'stay' }>();
const items = defineModel<{ question: string; answer: string; url?: string }[]>(
    { required: true },
);
const labels = computed(() => itemLabels[props.kind]);
</script>

<template>
    <div class="flex flex-col gap-4">
        <div v-for="(item, index) in items" :key="index" class="faq-item-field">
            <div class="flex flex-1 flex-col gap-2">
                <label :for="`${idPrefix}-q-${index}`" class="sr-only">{{
                    labels.name
                }}</label>
                <Input
                    :id="`${idPrefix}-q-${index}`"
                    v-model="item.question"
                    maxlength="160"
                    :placeholder="labels.name"
                />
                <label :for="`${idPrefix}-a-${index}`" class="sr-only">{{
                    labels.details
                }}</label>
                <textarea
                    :id="`${idPrefix}-a-${index}`"
                    v-model="item.answer"
                    rows="2"
                    maxlength="1000"
                    class="textarea min-h-20"
                    :placeholder="labels.details"
                />
                <template v-if="kind === 'stay'">
                    <label :for="`${idPrefix}-u-${index}`" class="sr-only">{{
                        itemLabels.stay.url
                    }}</label>
                    <Input
                        :id="`${idPrefix}-u-${index}`"
                        v-model="item.url"
                        type="url"
                        inputmode="url"
                        maxlength="255"
                        :placeholder="itemLabels.stay.url"
                    />
                </template>
            </div>
            <button
                type="button"
                class="icon-button"
                :aria-label="labels.remove"
                @click="items.splice(index, 1)"
            >
                <X class="size-4" />
            </button>
        </div>
        <button
            v-if="items.length < 12"
            type="button"
            class="link-underline hit-area inline-flex items-center gap-1.5 self-start text-sm"
            @click="items.push({ question: '', answer: '', url: '' })"
        >
            <Plus class="size-4" /> {{ labels.add }}
        </button>
    </div>
</template>
