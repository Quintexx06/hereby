<script setup lang="ts">
import { useClipboard } from '@vueuse/core';
import { Check, Link2 } from '@lucide/vue';
import { guestsPage } from '@/content/guests';

const props = defineProps<{ link: string; household: string }>();
const { copy, copied } = useClipboard({ copiedDuring: 1800 });
</script>

<template>
    <button
        type="button"
        class="pill-outline"
        :aria-label="`${guestsPage.copy}: ${household}`"
        @click="copy(props.link)"
    >
        <Check v-if="copied" class="size-4 text-success" />
        <Link2 v-else class="size-4" />
        <span aria-live="polite">{{
            copied ? guestsPage.copied : guestsPage.copy
        }}</span>
    </button>
</template>
