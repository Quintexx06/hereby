<script setup lang="ts">
import { ArrowUpRight, ChevronLeft, ChevronRight } from '@lucide/vue';
import { computed, ref } from 'vue';
import ShowcaseDialog from '@/components/marketing/ShowcaseDialog.vue';
import ShowcaseWindow from '@/components/marketing/ShowcaseWindow.vue';
import { useLoopingTrack } from '@/composables/motion/useLoopingTrack';
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';
import { dialogOrigin } from '@/lib/dialog-origin';
import { show as sampleSite } from '@/routes/samples';

defineProps<{ accent: string; couple: ShowcaseCoupleSite }>();

const templates = studio.templates;
/* Three copies for the endless loop; only the middle one is real to AT. */
const loop = [0, 1, 2].flatMap((copy) =>
    templates.map((template) => ({ template, copy })),
);
/* Starts with the middle template in the middle, its neighbours peeking. */
const { track, slides, index, current, jumping, moving, onScroll, go } =
    useLoopingTrack(templates.length, Math.floor(templates.length / 2));
const active = computed(() => templates[current.value]);
/* «Live ansehen»: the template as a whole site, with a reply that works. */
const sample = (slug: string) => sampleSite.url(slug);

/* The front window opens the whole site; a peeking one comes to the front. */
const opened = ref<(typeof templates)[number] | null>(null);
const dialogOpen = ref(false);
const origin = ref('50% 50%');

function open(slideIndex: number, event: MouseEvent): void {
    if (slideIndex !== index.value) {
        go(Math.sign(slideIndex - index.value));

        return;
    }

    origin.value = dialogOrigin(event);
    opened.value = loop[slideIndex].template;
    dialogOpen.value = true;
}
</script>

<!--
    The three templates side by side, endlessly: swipe or use the arrows.
    The windows stand still; a click opens the whole site in a popup.
    Nothing moves until the visitor does.
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
                    :active="slideIndex === index && !dialogOpen && !moving"
                    :accent="accent"
                    :couple="couple"
                    preview
                />
                <button
                    type="button"
                    class="showcase-open"
                    :aria-label="`${template.name}: ${studio.open}`"
                    :tabindex="slideIndex === index ? 0 : -1"
                    @click="open(slideIndex, $event)"
                >
                    <span class="showcase-open-badge">
                        {{ studio.open }}
                        <ArrowUpRight class="size-4" />
                    </span>
                </button>
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
                <a
                    :href="sample(active.slug)"
                    class="showcase-live"
                    target="_blank"
                    rel="noopener"
                    >{{ studio.live }} <ArrowUpRight class="size-4"
                /></a>
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

        <ShowcaseDialog
            v-model:open="dialogOpen"
            :template="opened"
            :accent="accent"
            :couple="couple"
            :origin="origin"
        />
    </div>
</template>
