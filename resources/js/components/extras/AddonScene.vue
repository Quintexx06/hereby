<script setup lang="ts">
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import type { AddonKey } from '@/content/extras';
import { addonScenes as scene } from '@/content/extras';
import { landingPhotos } from '@/content/landing-photos';

defineProps<{ kind: AddonKey }>();

const stack = [
    landingPhotos.sparklers,
    landingPhotos.tableCandles,
    landingPhotos.veilKiss,
];
</script>

<!--
    A small scene of what the extra does; the card's hover plays it
    (extras.css). Decorative: the card's text says the same.
-->
<template>
    <div class="addon-scene" :data-kind="kind" aria-hidden="true">
        <template v-if="kind === 'newspaper'">
            <div class="addon-paper addon-paper-back" />
            <div class="addon-paper">
                <p class="font-display text-xs font-bold">
                    {{ scene.newspaper.masthead }}
                </p>
                <ResponsivePhoto
                    :photo="landingPhotos.bouquet"
                    sizes="12rem"
                    class="aspect-[2/1] w-full rounded-sm object-cover"
                />
                <p class="text-xs font-semibold">{{ scene.newspaper.lead }}</p>
                <span class="addon-line" />
                <span class="addon-line w-2/3" />
            </div>
        </template>

        <ul v-else-if="kind === 'chase_service'" class="addon-chase">
            <li
                v-for="(name, index) in scene.chase.names"
                :key="name"
                :style="{ '--i': index }"
            >
                <span>{{ name }}</span>
                <span class="addon-chip">
                    <span class="addon-chip-open">{{ scene.chase.open }}</span>
                    <span class="addon-chip-done">{{ scene.chase.done }}</span>
                </span>
            </li>
        </ul>

        <div v-else-if="kind === 'custom_domain'" class="addon-browser">
            <span class="flex gap-1">
                <span v-for="dot in 3" :key="dot" class="addon-browser-dot" />
            </span>
            <span class="addon-url">
                <span class="addon-url-before">{{ scene.domain.before }}</span>
                <span
                    class="addon-url-after"
                    :style="{ '--chars': scene.domain.after.length }"
                    >{{ scene.domain.after }}</span
                >
            </span>
        </div>

        <div v-else class="addon-stack">
            <ResponsivePhoto
                v-for="(photo, index) in stack"
                :key="photo.src"
                :photo="photo"
                sizes="10rem"
                class="addon-stack-photo"
                :style="{ '--i': index - 1 }"
            />
        </div>
    </div>
</template>
