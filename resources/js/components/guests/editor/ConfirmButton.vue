<script setup lang="ts">
import { ref } from 'vue';
import { editCopy } from '@/content/guests';

/* A quiet two-step action: the first click asks, the second one acts. */
defineProps<{ label: string; question: string; busy?: boolean }>();
const emit = defineEmits<{ confirm: [] }>();
const asking = ref(false);

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
        <button type="button" class="pill-outline" @click="asking = false">
            {{ editCopy.cancel }}
        </button>
    </div>
    <button
        v-else
        type="button"
        class="link-underline self-start text-sm"
        :disabled="busy"
        @click="asking = true"
    >
        <slot />{{ label }}
    </button>
</template>
