<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { marquee } from '@/content/landing';
import { prefersReducedMotion } from '@/lib/motion';

/**
 * The word for "wedding" in every language a household might read, drifting
 * past. Scrolling speeds it up and turns it in the scroll's direction.
 */
const band = ref<HTMLElement | null>(null);
let lastY = 0;
let frame = 0;
let settle: ReturnType<typeof setTimeout> | undefined;

const animation = () => band.value?.getAnimations()[0];

const onScroll = () => {
    cancelAnimationFrame(frame);
    frame = requestAnimationFrame(() => {
        const delta = window.scrollY - lastY;
        lastY = window.scrollY;
        const running = animation();
        if (running) {
            running.playbackRate =
                Math.sign(delta || 1) * Math.min(1 + Math.abs(delta) / 12, 5);
        }
        clearTimeout(settle);
        settle = setTimeout(() => {
            const idle = animation();
            if (idle) {
                idle.playbackRate = Math.sign(idle.playbackRate || 1);
            }
        }, 160);
    });
};

onMounted(() => {
    if (prefersReducedMotion()) {
        return;
    }
    lastY = window.scrollY;
    window.addEventListener('scroll', onScroll, { passive: true });
});

onBeforeUnmount(() => {
    cancelAnimationFrame(frame);
    clearTimeout(settle);
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <div class="marquee" aria-hidden="true">
        <div ref="band" class="marquee-band">
            <template v-for="copy in 2" :key="copy">
                <span
                    v-for="word in marquee"
                    :key="`${copy}-${word}`"
                    class="marquee-word"
                >
                    {{ word }}<span class="marquee-dot">.</span>
                </span>
            </template>
        </div>
    </div>
</template>
