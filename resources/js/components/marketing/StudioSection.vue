<script setup lang="ts">
import { ChevronLeft, ChevronRight } from '@lucide/vue';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import StudioCard from '@/components/marketing/StudioCard.vue';
import { useAutoCycle } from '@/composables/motion/useAutoCycle';
import { studio } from '@/content/landing';

const total = studio.themes.length;
const { index, select, pause, resume } = useAutoCycle(total, 3600);
const active = computed(() => studio.themes[index.value]);

/** Fan position of each card relative to the one in front (-2 … 2). */
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
    Like stationery samples fanned out on a table: every card wears its own
    wedding theme, the chosen one comes forward and the section takes its colour.
-->
<template>
    <section
        class="studio"
        :data-theme="active.slug"
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
                    v-for="(theme, cardIndex) in studio.themes"
                    :key="theme.slug"
                    :slug="theme.slug"
                    :name="theme.name"
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
        </div>
    </section>
</template>
