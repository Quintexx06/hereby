<script setup lang="ts">
import { computed } from 'vue';
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import type { LandingPhoto } from '@/content/landing-photos';

const props = defineProps<{
    act: {
        numeral: string;
        name: string;
        when: string;
        place: string;
        guests: string;
        note: string;
        photo: LandingPhoto;
    };
    /** Distance from the card in focus: 0 in focus, ±1 one card away. */
    offset: number;
}>();

const parallax = computed(
    () => `translateX(${props.offset * -6}%) scale(1.14)`,
);
</script>

<template>
    <article class="act-card" :data-focus="Math.abs(offset) < 0.5">
        <div class="act-photo-frame">
            <ResponsivePhoto
                :photo="act.photo"
                sizes="(min-width: 1024px) 34rem, 85vw"
                class="act-photo"
                :style="{ transform: parallax }"
            />
            <span class="act-numeral" aria-hidden="true">{{
                act.numeral
            }}</span>
        </div>

        <div class="act-body">
            <p class="caption tabular-nums">{{ act.when }} · {{ act.place }}</p>
            <h3 class="act-name">{{ act.name }}</h3>
            <p class="act-guests">{{ act.guests }}</p>
            <p class="body-copy">{{ act.note }}</p>
        </div>
    </article>
</template>
