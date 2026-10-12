<script setup lang="ts">
import { ChevronLeft, ChevronRight } from '@lucide/vue';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import StudioCard from '@/components/marketing/StudioCard.vue';
import { useAutoCycle } from '@/composables/motion/useAutoCycle';
import { studio } from '@/content/landing';

const total = studio.templates.length;
const { index, select, pause, resume } = useAutoCycle(total, 4200);
const active = computed(() => studio.templates[index.value]);

/* Any template in any colour world: the swatches recolour cards and section. */
const theme = ref<string>('rose');

/** Fan position of each card relative to the one in front (-1 … 1). */
const half = Math.floor(total / 2);
const positionOf = (cardIndex: number) =>
    ((cardIndex - index.value + total + half) % total) - half;

const step = (direction: 1 | -1) =>
    select((index.value + direction + total) % total);

/** The arrows slide in once the deck is on screen, so it reads as a carousel. */
const deck = ref<HTMLElement | null>(null);
const inView = ref(false);
let observer: IntersectionObserver | undefined;
onMounted(() => {
    observer = new IntersectionObserver(
        ([entry]) => (inView.value = Boolean(entry?.isIntersecting)),
        { threshold: 0.35 },
    );
    if (deck.value) {
        observer.observe(deck.value);
    }
});
onBeforeUnmount(() => observer?.disconnect());
</script>

<!--
    The three templates fanned out like phones on a table, each showing its
    real first screen; the chosen one comes forward, and the swatches below
    recolour everything in one of the five colour worlds.
-->
<template>
    <section
        class="studio"
        :data-theme="theme"
        @mouseenter="pause"
        @mouseleave="resume"
    >
        <div
            class="page-container section flex flex-col items-center gap-14 text-center"
        >
            <div
                v-reveal
                class="flex max-w-3xl reveal flex-col items-center gap-6"
            >
                <h2 class="headline">{{ studio.title }}</h2>
                <p class="lede">{{ studio.lede }}</p>
            </div>

            <div
                ref="deck"
                class="deck"
                role="group"
                :aria-label="studio.hint"
                @keydown.left.prevent="step(-1)"
                @keydown.right.prevent="step(1)"
            >
                <StudioCard
                    v-for="(template, cardIndex) in studio.templates"
                    :key="template.slug"
                    :template="template.slug"
                    :theme="theme"
                    :name="template.name"
                    :position="positionOf(cardIndex)"
                    @select="select(cardIndex)"
                />
            </div>

            <div class="deck-controls" :data-in-view="inView">
                <button
                    type="button"
                    class="deck-arrow deck-arrow-prev"
                    :aria-label="studio.previous"
                    @click="step(-1)"
                >
                    <ChevronLeft class="size-5" />
                </button>

                <div
                    class="flex min-w-[12rem] flex-col items-center gap-2"
                    aria-live="polite"
                >
                    <Transition name="studio-flip" mode="out-in">
                        <p :key="active.slug" class="studio-active-name">
                            {{ active.name }}<span class="text-brand">.</span>
                        </p>
                    </Transition>
                    <p class="caption">{{ active.mood }}</p>
                </div>
                <button
                    type="button"
                    class="deck-arrow deck-arrow-next"
                    :aria-label="studio.next"
                    @click="step(1)"
                >
                    <ChevronRight class="size-5" />
                </button>
            </div>

            <div
                class="studio-swatches"
                role="radiogroup"
                :aria-label="studio.colours"
            >
                <button
                    v-for="option in studio.themes"
                    :key="option.slug"
                    type="button"
                    role="radio"
                    class="studio-swatch"
                    :data-theme="option.slug"
                    :aria-checked="theme === option.slug"
                    :aria-label="option.name"
                    :title="option.name"
                    @click="theme = option.slug"
                />
            </div>
        </div>
    </section>
</template>
