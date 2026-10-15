<script setup lang="ts">
import { Plus } from '@lucide/vue';
import { topics as copy } from '@/content/landing';
import type { FaqItem } from '@/types/landing';

defineProps<{ items: FaqItem[] }>();
</script>

<!-- Native <details>, mirrored as FAQPage data in the head. -->
<template>
    <section class="topic-section" aria-labelledby="topic-faq">
        <h2 id="topic-faq" class="title">{{ copy.faq }}</h2>
        <div class="border-b border-rule">
            <details
                v-for="item in items"
                :key="item.question"
                class="faq-item"
            >
                <summary class="faq-question">
                    {{ item.question }}
                    <Plus class="faq-icon" aria-hidden="true" />
                </summary>
                <p class="body-copy pb-8">{{ item.answer }}</p>
            </details>
        </div>
    </section>
</template>
