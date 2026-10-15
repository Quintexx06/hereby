<script setup lang="ts">
import type { TopicSection } from '@/types/landing';

defineProps<{ section: TopicSection }>();
</script>

<!-- One H2 of a topic page: a few paragraphs, or points side by side. -->
<template>
    <section v-reveal class="topic-section reveal">
        <h2 class="title">{{ section.title }}</h2>
        <div class="flex flex-col gap-6">
            <p
                v-for="paragraph in section.body ?? []"
                :key="paragraph"
                class="body-copy"
            >
                {{ paragraph }}
            </p>
            <ul v-if="section.points?.length" class="topic-points">
                <li v-for="point in section.points" :key="point.title">
                    <h3 class="font-semibold">{{ point.title }}</h3>
                    <p class="text-muted-foreground">{{ point.body }}</p>
                </li>
            </ul>
        </div>
    </section>
</template>
