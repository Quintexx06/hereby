<script setup lang="ts" generic="T extends string | boolean">
/* A radio group drawn as chips: big targets, native keyboard behaviour. */
defineProps<{
    name: string;
    label: string;
    options: { value: T; label: string }[];
    disabled?: boolean;
}>();
const model = defineModel<T | null>({ required: true });
</script>

<template>
    <div role="radiogroup" :aria-label="label" class="choice-row">
        <label
            v-for="option in options"
            :key="String(option.value)"
            class="chip"
        >
            <input
                v-model="model"
                type="radio"
                class="sr-only"
                :name="name"
                :value="option.value"
                :disabled="disabled"
            />
            {{ option.label }}
        </label>
    </div>
</template>
