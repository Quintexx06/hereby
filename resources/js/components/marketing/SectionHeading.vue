<script setup lang="ts">
defineProps<{
    title: string;
    lede?: string;
}>();
</script>

<template>
    <header class="flex max-w-4xl flex-col gap-6">
        <h2 class="headline">{{ title }}</h2>
        <p v-if="lede" class="lede">{{ lede }}</p>
    </header>
</template>
