<script setup lang="ts">
import { ChevronLeft, ChevronRight } from '@lucide/vue';
import { computed, ref } from 'vue';
import ShowcaseWindow from '@/components/marketing/ShowcaseWindow.vue';
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';
import { prefersReducedMotion } from '@/lib/motion';

defineProps<{ accent: string; couple: ShowcaseCoupleSite }>();

const templates = studio.templates;
const track = ref<HTMLElement | null>(null);
const slides = ref<HTMLElement[]>([]);
const index = ref(0);
const active = computed(() => templates[index.value]);

/* The slide nearest the middle of the track is the one in view. */
function onScroll(): void {
    const el = track.value;
    if (!el) {
        return;
    }

    const middle = el.scrollLeft + el.clientWidth / 2;
    const distances = slides.value.map((slide) =>
        Math.abs(slide.offsetLeft + slide.offsetWidth / 2 - middle),
    );
    index.value = distances.indexOf(Math.min(...distances));
}

function go(next: number): void {
    const el = track.value;
    const slide = slides.value[(next + templates.length) % templates.length];
    if (!el || !slide) {
        return;
    }

    el.scrollTo({
        left: slide.offsetLeft - (el.clientWidth - slide.offsetWidth) / 2,
        behavior: prefersReducedMotion() ? 'auto' : 'smooth',
    });
}
</script>

<!--
    The three templates side by side: swipe or use the arrows; each window
    scrolls on its own. Nothing moves until the visitor does.
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
            @scroll.passive="onScroll"
            @keydown.left.prevent="go(index - 1)"
            @keydown.right.prevent="go(index + 1)"
        >
            <div
                v-for="(template, slideIndex) in templates"
                :key="template.slug"
                ref="slides"
                class="showcase-slide"
                role="group"
                aria-roledescription="slide"
                :aria-label="template.name"
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
                @click="go(index - 1)"
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
                        :data-active="dotIndex === index"
                    />
                </div>
            </div>
            <button
                type="button"
                class="showcase-arrow"
                :aria-label="studio.next"
                @click="go(index + 1)"
            >
                <ChevronRight class="size-5" />
            </button>
        </div>
    </div>
</template>
