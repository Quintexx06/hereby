<script setup lang="ts">
import { computed, ref } from 'vue';
import MiniAtelier from '@/components/marketing/studio/MiniAtelier.vue';
import MiniGarten from '@/components/marketing/studio/MiniGarten.vue';
import MiniPanorama from '@/components/marketing/studio/MiniPanorama.vue';
import { useTilt } from '@/composables/motion/useTilt';

const props = defineProps<{
    template: 'atelier' | 'garten' | 'panorama';
    theme: string;
    name: string;
    /** Place in the fan: 0 in front, ±1 to the sides. */
    position: number;
}>();

defineEmits<{ select: [] }>();

const minis = {
    atelier: MiniAtelier,
    garten: MiniGarten,
    panorama: MiniPanorama,
};
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
    One template as a phone-sized first screen, in the chosen colour world.
    A click brings it to the front; the front one leans to the pointer and
    wears a moving border light.
-->
<template>
    <button
        type="button"
        class="deck-card"
        :data-theme="theme"
        :data-front="isFront"
        :style="fan"
        :aria-label="`Vorlage ${name}`"
        :aria-pressed="isFront"
        @click="$emit('select')"
        @pointermove="isFront && onMove($event)"
        @pointerleave="onLeave"
    >
        <span
            ref="card"
            class="deck-card-paper deck-card-screen"
            :style="isFront ? { transform: tilt } : {}"
        >
            <component :is="minis[template]" />
            <span class="deck-card-label">{{ name }}</span>
        </span>
    </button>
</template>
