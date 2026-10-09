<script setup lang="ts">
import { ArrowDown, ArrowUp } from '@lucide/vue';
import { contentPage as copy } from '@/content/content';

defineProps<{ first: boolean; last: boolean }>();
defineEmits<{ move: [direction: -1 | 1] }>();
</script>

<template>
    <div class="flex gap-1">
        <button
            type="button"
            class="icon-button disabled:opacity-30"
            :aria-label="copy.up"
            :disabled="first"
            @click="$emit('move', -1)"
        >
            <ArrowUp class="size-4" />
        </button>
        <button
            type="button"
            class="icon-button disabled:opacity-30"
            :aria-label="copy.down"
            :disabled="last"
            @click="$emit('move', 1)"
        >
            <ArrowDown class="size-4" />
        </button>
    </div>
</template>
