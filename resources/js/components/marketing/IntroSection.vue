<script setup lang="ts">
import RingsCanvas from '@/components/marketing/RingsCanvas.vue';
import { intro } from '@/content/landing';
</script>

<template>
    <section class="page-container section intro-grid">
        <div v-reveal class="flex reveal flex-col gap-10">
            <h2 class="headline max-w-[22ch] text-5xl">{{ intro.title }}</h2>
            <p class="body-copy">{{ intro.body }}</p>
        </div>
        <RingsCanvas />
    </section>
</template>
