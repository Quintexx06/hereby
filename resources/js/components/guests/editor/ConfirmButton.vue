<script setup lang="ts">
import { nextTick, ref, useTemplateRef } from 'vue';
import { editCopy } from '@/content/guests';

/*
 * A quiet two-step action: the first click asks, the second one acts.
 * Focus follows the swap, so keyboard and screen-reader users keep their place.
 */
defineProps<{ label: string; question: string; busy?: boolean }>();
const emit = defineEmits<{ confirm: [] }>();
const asking = ref(false);
const trigger = useTemplateRef<HTMLButtonElement>('trigger');
const cancelButton = useTemplateRef<HTMLButtonElement>('cancelButton');

async function ask(): Promise<void> {
    asking.value = true;
    await nextTick();
    cancelButton.value?.focus();
}

async function cancel(): Promise<void> {
    asking.value = false;
    await nextTick();
    trigger.value?.focus();
}

function confirm(): void {
    asking.value = false;
    emit('confirm');
}
</script>

<template>
    <div v-if="asking" class="confirm-row" role="group" :aria-label="question">
        <span>{{ question }}</span>
        <button
            type="button"
            class="pill-outline border-destructive text-destructive"
            :disabled="busy"
            @click="confirm"
        >
            {{ editCopy.confirm }}
        </button>
        <button
            ref="cancelButton"
            type="button"
            class="pill-outline"
            @click="cancel"
        >
            {{ editCopy.cancel }}
        </button>
    </div>
    <button
        v-else
        ref="trigger"
        type="button"
        class="link-underline hit-area self-start text-sm"
        :disabled="busy"
        @click="ask"
    >
        <slot />{{ label }}
    </button>
</template>
