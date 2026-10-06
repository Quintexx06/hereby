<script setup lang="ts">
import SectionHeading from '@/components/marketing/SectionHeading.vue';
import { principles } from '@/content/landing';
</script>

<template>
    <section class="page-container section">
        <SectionHeading
            v-reveal
            class="reveal"
            :eyebrow="principles.eyebrow"
            :title="principles.title"
        />

        <ol class="mt-16 grid gap-10 md:grid-cols-3">
            <li
                v-for="(item, index) in principles.items"
                :key="item.title"
                v-reveal="index * 90"
                class="feature-card reveal"
            >
                <span class="feature-index">§ {{ index + 1 }}</span>
                <h3 class="text-2xl">{{ item.title }}</h3>
                <p class="text-muted-foreground">{{ item.body }}</p>
            </li>
        </ol>
    </section>
</template>
