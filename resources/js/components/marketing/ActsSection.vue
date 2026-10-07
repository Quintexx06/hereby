<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import ActCard from '@/components/marketing/ActCard.vue';
import { useScrollProgress } from '@/composables/motion/useScrollProgress';
import { acts } from '@/content/landing';

const section = ref<HTMLElement | null>(null);
const track = ref<HTMLElement | null>(null);
const progress = useScrollProgress(section, 'pinned');

/** Width of one card plus gap; 0 below lg, where the track is a swipe carousel. */
const step = ref(0);
const measure = () => {
    const wide = window.matchMedia('(min-width: 1024px)').matches;
    const [first, second] = Array.from(
        track.value?.children ?? [],
    ) as HTMLElement[];
    step.value =
        wide && first && second ? second.offsetLeft - first.offsetLeft : 0;
};
onMounted(() => {
    measure();
    window.addEventListener('resize', measure, { passive: true });
});
onBeforeUnmount(() => window.removeEventListener('resize', measure));

const total = acts.items.length;
const focus = computed(() => progress.value * (total - 1));
const current = computed(() => Math.round(focus.value) + 1);
/** Scroll moves exactly one act per step, so the act in focus is always aligned. */
const trackStyle = computed(() =>
    step.value
        ? { transform: `translate3d(${-focus.value * step.value}px, 0, 0)` }
        : {},
);
</script>

<!-- The wedding day as a play: scroll moves the acts sideways (pinned on desktop). -->
<template>
    <section
        id="tag"
        ref="section"
        class="acts stage"
        :style="{ '--acts': total }"
    >
        <div class="acts-sticky">
            <div class="page-container acts-head">
                <div class="flex flex-col gap-4">
                    <h2 class="headline">{{ acts.title }}</h2>
                    <p class="lede">{{ acts.lede }}</p>
                </div>
                <div class="acts-meter" aria-hidden="true">
                    <p class="acts-counter">
                        <span class="text-foreground">{{
                            String(current).padStart(2, '0')
                        }}</span>
                        / {{ String(total).padStart(2, '0') }}
                    </p>
                    <span class="acts-bar"
                        ><span
                            class="acts-bar-fill"
                            :style="{ transform: `scaleX(${progress})` }"
                    /></span>
                    <p class="caption">{{ acts.example }}</p>
                </div>
            </div>

            <div ref="track" class="acts-track" :style="trackStyle">
                <ActCard
                    v-for="(act, index) in acts.items"
                    :key="act.name"
                    :act="act"
                    :offset="step ? index - focus : 0"
                />
            </div>
        </div>
    </section>
</template>
