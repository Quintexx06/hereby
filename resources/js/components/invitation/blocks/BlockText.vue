<script setup lang="ts">
import { computed } from 'vue';

/* The couple's text as plain paragraphs: a blank line starts a new one. */
const props = defineProps<{ text: string | null }>();
const paragraphs = computed(() =>
    (props.text ?? '')
        .split(/\n\s*\n/)
        .map((paragraph) => paragraph.trim())
        .filter(Boolean),
);
</script>

<template>
    <div class="block-text">
        <p v-for="(paragraph, index) in paragraphs" :key="index">
            {{ paragraph }}
        </p>
    </div>
</template>
