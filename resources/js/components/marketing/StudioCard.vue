<script setup lang="ts">
import { computed, ref } from 'vue';
import type { HerebyIconName } from '@/components/brand/HerebyIcon.vue';
import HerebyIcon from '@/components/brand/HerebyIcon.vue';
import { useTilt } from '@/composables/motion/useTilt';
import { previewHouseholds } from '@/content/invitation-preview';

const props = defineProps<{
    slug: string;
    name: string;
    /** Place in the fan: 0 in front, ±1, ±2 to the sides. */
    position: number;
}>();

defineEmits<{ select: [] }>();

const household = previewHouseholds[0];
const eventIcons: HerebyIconName[] = ['glass', 'dinner', 'music'];
const card = ref<HTMLElement | null>(null);
const { transform: tilt, onMove, onLeave } = useTilt(card, 6);

const isFront = computed(() => props.position === 0);
const fan = computed(() => ({
    '--pos': props.position,
    '--depth': Math.abs(props.position),
    zIndex: 10 - Math.abs(props.position),
}));
</script>

<!--
    One printed invitation in its own theme. A click brings it to the front;
    the front card leans to the pointer and wears a moving border light.
-->
<template>
    <button
        type="button"
        class="deck-card"
        :data-theme="slug"
        :data-front="isFront"
        :style="fan"
        :aria-label="`Thema ${name}`"
        :aria-pressed="isFront"
        @click="$emit('select')"
        @pointermove="isFront && onMove($event)"
        @pointerleave="onLeave"
    >
        <span
            ref="card"
            class="deck-card-paper"
            :style="isFront ? { transform: tilt } : {}"
        >
            <span class="caption">{{ household.greeting }}</span>
            <span class="deck-card-names"
                >Anna<br /><span class="text-brand">&amp;</span> Luca</span
            >
            <span class="text-base font-medium"
                >heiraten · {{ household.date }}</span
            >

            <span class="mt-auto flex flex-col pt-7">
                <span
                    v-for="(event, index) in household.events"
                    :key="event.name"
                    class="deck-card-event"
                >
                    <HerebyIcon
                        :name="eventIcons[index] ?? 'glass'"
                        class="size-5 text-brand"
                    />
                    <span class="font-semibold text-brand tabular-nums">{{
                        event.time
                    }}</span>
                    <span class="font-semibold">{{ event.name }}</span>
                </span>
            </span>
            <span class="phone-action mt-5">{{ household.action }}</span>
            <span class="deck-card-label">{{ name }}</span>
        </span>
    </button>
</template>
