<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import { Button } from '@/components/ui/button';
import { useClosingVeil } from '@/composables/motion/useClosingVeil';
import { closing } from '@/content/landing';
import { register } from '@/routes';

const section = ref<HTMLElement | null>(null);
const canvas = ref<HTMLCanvasElement | null>(null);
const { isLive } = useClosingVeil(canvas, section);
</script>

<!-- Bookend to the hero: as the page ends, the veil drifts back in. -->
<template>
    <section ref="section" class="closing stage">
        <ResponsivePhoto :photo="closing.photo" class="closing-photo" />
        <canvas
            ref="canvas"
            class="closing-canvas"
            :class="{ 'opacity-100': isLive }"
            aria-hidden="true"
        />
        <div class="closing-scrim" aria-hidden="true" />
        <div
            v-reveal
            class="page-container flex reveal flex-col items-start gap-8 pb-20 sm:pb-28"
        >
            <h2 class="display max-w-[16ch]">{{ closing.title }}</h2>
            <p class="lede text-foreground/85">{{ closing.lede }}</p>
            <Button as-child size="pill">
                <Link :href="register()">{{ closing.cta }}</Link>
            </Button>
        </div>
    </section>
</template>
