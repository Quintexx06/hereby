<script setup lang="ts">
import { ChevronLeft, ChevronRight } from '@lucide/vue';
import { computed } from 'vue';
import ShowcaseWindow from '@/components/marketing/ShowcaseWindow.vue';
import { useLoopingTrack } from '@/composables/motion/useLoopingTrack';
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';

defineProps<{ accent: string; couple: ShowcaseCoupleSite }>();

const templates = studio.templates;
/* Three copies for the endless loop; only the middle one is real to AT. */
const loop = [0, 1, 2].flatMap((copy) =>
    templates.map((template) => ({ template, copy })),
);
/* Starts with the middle template in the middle, its neighbours peeking. */
const { track, slides, index, current, jumping, onScroll, go } =
    useLoopingTrack(templates.length, Math.floor(templates.length / 2));
const active = computed(() => templates[current.value]);
</script>

<!--
    The three templates side by side, endlessly: swipe or use the arrows;
    each window scrolls on its own. Nothing moves until the visitor does.
-->
<template>
    <div
        class="flex flex-col gap-8"
        role="region"
        aria-roledescription="carousel"
        :aria-label="studio.templatesLabel"
    >
        <div
            ref="track"
            class="showcase-track"
            :data-jumping="jumping"
            @scroll.passive="onScroll"
            @keydown.left.prevent="go(-1)"
            @keydown.right.prevent="go(1)"
        >
            <div
                v-for="({ template, copy }, slideIndex) in loop"
                :key="`${copy}-${template.slug}`"
                :ref="(el) => (slides[slideIndex] = el as HTMLElement)"
                class="showcase-slide"
                role="group"
                aria-roledescription="slide"
                :aria-label="template.name"
                :aria-hidden="copy !== 1 || undefined"
                :inert="copy !== 1 && slideIndex !== index"
                :data-active="slideIndex === index"
            >
                <ShowcaseWindow
                    :template="template.slug"
                    :active="slideIndex === index"
                    :accent="accent"
                    :couple="couple"
                />
            </div>
        </div>

        <div class="showcase-controls">
            <button
                type="button"
                class="showcase-arrow"
                :aria-label="studio.previous"
                @click="go(-1)"
            >
                <ChevronLeft class="size-5" />
            </button>
            <div
                class="flex min-w-0 flex-col items-center gap-1.5 text-center"
                aria-live="polite"
            >
                <Transition name="studio-flip" mode="out-in">
                    <p :key="active.slug" class="showcase-template-name">
                        {{ active.name }}<span class="text-brand">.</span>
                    </p>
                </Transition>
                <p class="caption">{{ active.mood }}</p>
                <div class="flex gap-2 pt-1" aria-hidden="true">
                    <span
                        v-for="(template, dotIndex) in templates"
                        :key="template.slug"
                        class="showcase-dot"
                        :data-active="dotIndex === current"
                    />
                </div>
            </div>
            <button
                type="button"
                class="showcase-arrow"
                :aria-label="studio.next"
                @click="go(1)"
            >
                <ChevronRight class="size-5" />
            </button>
        </div>
    </div>
</template>
