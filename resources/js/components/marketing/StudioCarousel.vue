<script setup lang="ts">
import { ArrowUpRight, ChevronLeft, ChevronRight } from '@lucide/vue';
import { computed } from 'vue';
import StudioSlide from '@/components/marketing/StudioSlide.vue';
import { useLoopingTrack } from '@/composables/motion/useLoopingTrack';
import { studio } from '@/content/landing';
import { templates } from '@/content/setup';
import { show as sample } from '@/routes/samples';
import type { WeddingTemplate } from '@/types';

const designs = Object.keys(templates) as WeddingTemplate[];
/* Three copies for the endless loop; only the middle one is real to AT. */
const loop = [0, 1, 2].flatMap((copy) =>
    designs.map((design) => ({ design, copy })),
);
const { track, slides, index, current, jumping, onScroll, go } =
    useLoopingTrack(designs.length);
const active = computed(() => designs[current.value]);

/* A design's name goes the short way round to it. */
function pick(target: number): void {
    const count = designs.length;
    const half = Math.floor(count / 2);
    const step =
        ((((target - current.value) % count) + count + half) % count) - half;

    if (step !== 0) {
        go(step);
    }
}
</script>

<!--
    The five designs side by side, endlessly: the front window large, its
    neighbours peeking at either side. Swipe, use the arrows or a name; the
    front window opens the whole sample site. Nothing moves on its own.
-->
<template>
    <div
        class="flex flex-col gap-8"
        role="region"
        aria-roledescription="carousel"
        :aria-label="studio.designsLabel"
    >
        <div
            ref="track"
            class="studio-track"
            :data-jumping="jumping"
            @scroll.passive="onScroll"
            @keydown.left.prevent="go(-1)"
            @keydown.right.prevent="go(1)"
        >
            <div
                v-for="({ design, copy }, slideIndex) in loop"
                :key="`${copy}-${design}`"
                :ref="(el) => (slides[slideIndex] = el as HTMLElement)"
                class="studio-slide"
                role="group"
                aria-roledescription="slide"
                :aria-label="templates[design].name"
                :aria-hidden="copy !== 1 || undefined"
                :inert="copy !== 1 && slideIndex !== index"
                :data-active="slideIndex === index"
            >
                <StudioSlide
                    :design="design"
                    :active="slideIndex === index"
                    @step="go(Math.sign(slideIndex - index))"
                />
            </div>
        </div>

        <div class="studio-controls">
            <button
                type="button"
                class="studio-arrow"
                :aria-label="studio.previous"
                @click="go(-1)"
            >
                <ChevronLeft class="size-5" />
            </button>
            <div class="studio-caption" aria-live="polite">
                <Transition name="studio-flip" mode="out-in">
                    <p :key="active" class="studio-name">
                        {{ templates[active].name
                        }}<span class="text-brand">.</span>
                    </p>
                </Transition>
                <p class="max-w-xl text-muted-foreground">
                    {{ templates[active].mood }}
                </p>
                <a
                    :href="sample.url(active)"
                    class="studio-live"
                    target="_blank"
                    rel="noopener"
                    >{{ studio.live }} <ArrowUpRight class="size-4"
                /></a>
            </div>
            <button
                type="button"
                class="studio-arrow"
                :aria-label="studio.next"
                @click="go(1)"
            >
                <ChevronRight class="size-5" />
            </button>
        </div>

        <div class="studio-names">
            <button
                v-for="(design, designIndex) in designs"
                :key="design"
                type="button"
                class="studio-chip"
                :aria-current="design === active || undefined"
                @click="pick(designIndex)"
            >
                {{ templates[design].name }}
            </button>
        </div>
    </div>
</template>
