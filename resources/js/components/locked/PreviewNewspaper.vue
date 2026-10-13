<script setup lang="ts">
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import { landingPhotos } from '@/content/landing-photos';
import { previewNewspaper as paper } from '@/content/locked-previews';
</script>

<!-- A front page of the wedding newspaper, set by the witnesses. -->
<template>
    <div class="locked-preview-tile gap-5">
        <div
            class="flex items-baseline justify-between border-b border-rule pb-3"
        >
            <p class="font-display text-2xl font-bold">{{ paper.masthead }}</p>
            <p class="caption">{{ paper.issue }}</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-[3fr_2fr]">
            <div class="flex flex-col gap-3">
                <ResponsivePhoto
                    :photo="landingPhotos.veilKiss"
                    sizes="24rem"
                    class="aspect-[4/3] w-full rounded-md object-cover"
                />
                <p class="font-display text-xl font-bold">{{ paper.lead }}</p>
            </div>
            <ul class="flex flex-col gap-4">
                <li
                    v-for="story in paper.stories"
                    :key="story"
                    class="flex flex-col gap-2 border-b border-rule pb-4"
                >
                    <span class="font-bold">{{ story }}</span>
                    <span class="h-2 w-full rounded bg-muted" />
                    <span class="h-2 w-2/3 rounded bg-muted" />
                </li>
            </ul>
        </div>
    </div>
</template>
