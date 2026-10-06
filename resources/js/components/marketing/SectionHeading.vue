<script setup lang="ts">
defineProps<{
    eyebrow?: string;
    title: string;
    lede?: string;
}>();
</script>

<template>
    <header class="flex max-w-2xl flex-col gap-4">
        <p v-if="eyebrow" class="eyebrow">{{ eyebrow }}</p>
        <h2 class="display-lg">{{ title }}</h2>
        <p v-if="lede" class="lede">{{ lede }}</p>
    </header>
</template>
