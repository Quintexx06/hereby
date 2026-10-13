<script setup lang="ts">
import { ref } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';

/** Plays once per hover and always runs to the end, even if the pointer leaves. */
const isPlaying = ref(false);
const play = () => {
    if (!prefersReducedMotion()) {
        isPlaying.value = true;
    }
};
</script>

<!--
    The Hereby wordmark in wide Archivo, closed with a blush full stop.
    On hover it replays the footer's entrance: the letters rise one by one
    and the full stop drops in with a bounce.
-->
<template>
    <span
        class="wordmark wordmark-stage text-foreground"
        :data-playing="isPlaying"
        @pointerenter="play"
        ><span
            v-for="(letter, index) in 'hereby'"
            :key="index"
            class="wordmark-rise"
            :style="{ '--i': index }"
            >{{ letter }}</span
        ><span
            class="wordmark-stop wordmark-drop-in"
            @animationend="isPlaying = false"
            >.</span
        ></span
    >
</template>
