<script setup lang="ts">
import { computed, ref } from 'vue';
import SectionHeading from '@/components/marketing/SectionHeading.vue';
import StepItem from '@/components/marketing/StepItem.vue';
import { useScrollProgress } from '@/composables/motion/useScrollProgress';
import { steps } from '@/content/landing';
import { prefersReducedMotion } from '@/lib/motion';

const list = ref<HTMLElement | null>(null);
const through = useScrollProgress(list, 'through');

/**
 * A spark travels along the thread while the steps cross the viewport;
 * each step lights up, counts up and rises in as the spark reaches it.
 */
const travel = computed(() =>
    prefersReducedMotion()
        ? 1
        : Math.min(Math.max((through.value - 0.12) / 0.42, 0), 1),
);
const thresholds = steps.items.map((_, index) => index / steps.items.length);
</script>

<template>
    <section id="ablauf" class="stage">
        <div class="page-container section flex flex-col gap-16 sm:gap-20">
            <SectionHeading v-reveal class="reveal" :title="steps.title" />
            <div ref="list" class="relative" :style="{ '--travel': travel }">
                <span class="steps-thread" aria-hidden="true">
                    <span class="steps-thread-fill" />
                    <span class="steps-spark" />
                </span>
                <ol class="grid gap-14 md:grid-cols-3 md:gap-10">
                    <StepItem
                        v-for="(step, index) in steps.items"
                        :key="step.title"
                        v-bind="step"
                        :active="travel >= thresholds[index] + 0.02"
                    />
                </ol>
            </div>
        </div>
    </section>
</template>
