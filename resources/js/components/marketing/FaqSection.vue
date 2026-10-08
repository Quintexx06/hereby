<script setup lang="ts">
import { Plus } from '@lucide/vue';
import AskQuestionForm from '@/components/marketing/AskQuestionForm.vue';
import RosesCanvas from '@/components/marketing/RosesCanvas.vue';
import { faq } from '@/content/landing';
import type { FaqItem } from '@/types/landing';

defineProps<{
    items: FaqItem[];
}>();
</script>

<!-- Native <details>: works without JS and stays indexable (FAQPage data in the head). -->
<template>
    <section
        id="fragen"
        class="page-container section grid gap-14 lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)] lg:gap-20"
    >
        <div class="faq-card">
            <h2 v-reveal class="faq-heading reveal">{{ faq.title }}</h2>
            <RosesCanvas />
            <AskQuestionForm class="mt-auto" />
        </div>
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
