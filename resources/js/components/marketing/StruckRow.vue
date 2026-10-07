<script setup lang="ts">
import { computed, ref } from 'vue';
import type { HerebyIconName } from '@/components/brand/HerebyIcon.vue';
import HerebyIcon from '@/components/brand/HerebyIcon.vue';
import { useScrollProgress } from '@/composables/motion/useScrollProgress';

const props = defineProps<{
    word: string;
    icon: HerebyIconName;
    instead: string;
    /** Row number: picks this row's own hand-drawn stroke. */
    index: number;
}>();

/** Six strokes, like crossing out by hand: never the same angle twice. */
const strokes = [
    { tilt: -3, top: '54%', d: 'M1 6 C 30 4.5, 62 5.5, 99 3.2' },
    { tilt: 2.2, top: '46%', d: 'M1 4 C 28 6.4, 70 3.6, 99 5.2' },
    { tilt: -1.2, top: '58%', d: 'M1 5.5 C 35 3.4, 58 7, 99 4' },
    { tilt: 3.4, top: '50%', d: 'M1 3.6 C 40 5, 66 6.2, 99 5.6' },
    { tilt: -4.2, top: '57%', d: 'M1 6.2 C 24 5.6, 54 3.2, 99 4.4' },
    { tilt: 1.4, top: '48%', d: 'M1 4.6 C 31 3.6, 72 6.6, 99 4.8' },
];

const row = ref<HTMLElement | null>(null);
const progress = useScrollProgress(row, 'enter');
const strike = computed(() =>
    Math.min(Math.max((progress.value - 0.35) / 0.5, 0), 1),
);
const stroke = computed(() => strokes[props.index % strokes.length]);
</script>

<!--
    A word crossed out by hand as it scrolls into view; its icon pops out of
    the word once struck, and jumps bigger on hover.
-->
<template>
    <li ref="row" class="struck-row" :data-struck="strike >= 1">
        <p class="struck-word">
            <span class="relative inline-block">
                <del class="no-underline">{{ word }}</del>
                <svg
                    class="struck-line"
                    viewBox="0 0 100 10"
                    preserveAspectRatio="none"
                    aria-hidden="true"
                    :style="{
                        '--strike': strike,
                        '--strike-tilt': `${stroke.tilt}deg`,
                        '--strike-top': stroke.top,
                    }"
                >
                    <path
                        :d="stroke.d"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.2"
                        stroke-linecap="round"
                    />
                </svg>
                <span class="struck-badge" aria-hidden="true">
                    <HerebyIcon :name="icon" />
                </span>
            </span>
        </p>
        <p class="struck-instead">{{ instead }}</p>
    </li>
</template>
