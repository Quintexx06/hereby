<script setup lang="ts">
import { Monitor, Moon, Sun } from '@lucide/vue';
import { useAppearance } from '@/composables/useAppearance';
import { appearanceCopy } from '@/content/account';

const { appearance, updateAppearance } = useAppearance();

const options = [
    { value: 'light', Icon: Sun },
    { value: 'dark', Icon: Moon },
    { value: 'system', Icon: Monitor },
] as const;
</script>

<template>
    <div class="segmented" role="group" :aria-label="appearanceCopy.title">
        <button
            v-for="{ value, Icon } in options"
            :key="value"
            type="button"
            class="segmented-option"
            :aria-pressed="appearance === value"
            @click="updateAppearance(value)"
        >
            <component :is="Icon" class="size-4" aria-hidden="true" />
            {{ appearanceCopy.options[value] }}
        </button>
    </div>
</template>
