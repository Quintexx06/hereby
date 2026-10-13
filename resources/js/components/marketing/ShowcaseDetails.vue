<script setup lang="ts">
import { Check } from '@lucide/vue';
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import { studio } from '@/content/landing';

const site = studio.site;
/** Snapshots scattered on the table, each at its own slight angle. */
const tilts = ['-4deg', '3deg', '-2deg'];
</script>

<!-- Below the hero: welcome, programme, travel and the guests' photos. -->
<template>
    <div class="showcase-body">
        <div class="flex flex-col gap-8">
            <p class="showcase-welcome">{{ site.welcome }}</p>
            <div>
                <p class="showcase-label">{{ site.programmeTitle }}</p>
                <ol>
                    <li
                        v-for="event in site.events"
                        :key="event.time"
                        class="showcase-event"
                    >
                        <span class="font-semibold text-brand tabular-nums">{{
                            event.time
                        }}</span>
                        <span class="font-semibold">{{ event.name }}</span>
                        <span class="text-muted-foreground">{{
                            event.place
                        }}</span>
                    </li>
                </ol>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <ResponsivePhoto
                :photo="site.photos.venue"
                sizes="(min-width: 1024px) 30rem, 90vw"
                class="showcase-venue-photo"
            />
            <div>
                <p class="showcase-label">{{ site.travelTitle }}</p>
                <p class="text-sm leading-relaxed text-muted-foreground">
                    {{ site.travel }}
                </p>
            </div>
        </div>

        <div class="showcase-gallery">
            <p class="showcase-label">{{ site.galleryTitle }}</p>
            <div class="flex justify-center gap-3 sm:gap-5">
                <span
                    v-for="(photo, index) in site.photos.gallery"
                    :key="photo.src"
                    class="showcase-snapshot"
                    :style="{ '--tilt': tilts[index] }"
                >
                    <ResponsivePhoto
                        :photo="photo"
                        sizes="12rem"
                        class="aspect-[4/5] w-full object-cover"
                    />
                </span>
            </div>
            <p class="showcase-reply">
                <Check class="size-4 text-brand" />
                {{ site.replyNote }}
            </p>
        </div>
    </div>
</template>
