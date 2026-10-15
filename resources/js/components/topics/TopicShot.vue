<script setup lang="ts">
import { topics as copy } from '@/content/landing';
import type { TopicShot } from '@/types/landing';

defineProps<{ shot: TopicShot }>();
</script>

<!--
    The product itself, as a screenshot with demo data: a phone for what
    guests see, a browser window for the couple's side.
-->
<template>
    <figure v-reveal class="topic-shot reveal" :data-frame="shot.frame">
        <div class="topic-shot-device">
            <div
                v-if="shot.frame === 'browser'"
                class="topic-shot-bar"
                aria-hidden="true"
            >
                <span /><span /><span />
            </div>
            <img
                :src="shot.src"
                :width="shot.width"
                :height="shot.height"
                :alt="shot.alt"
                loading="lazy"
                decoding="async"
                class="block h-auto w-full"
            />
        </div>
        <figcaption class="caption text-center text-pretty">
            {{ shot.caption }} · {{ copy.demo }}
        </figcaption>
    </figure>
</template>
