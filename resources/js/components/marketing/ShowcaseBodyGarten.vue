<script setup lang="ts">
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import { studio } from '@/content/landing';

const site = studio.site;
/** Prints dropped on the table, each settling at its own angle. */
const tilts = ['-5deg', '3deg', '-2deg'];
</script>

<!--
    Garten below the hero: soft and centred. The welcome under a twig, the
    day as a vine with leaves unfurling, the venue under an arch, prints
    that drop and settle, and an arched card for the reply.
-->
<template>
    <div class="sc-g">
        <section v-reveal class="sc-g-welcome sc-rise">
            <svg
                class="sc-g-sprig"
                viewBox="0 0 120 24"
                fill="none"
                aria-hidden="true"
            >
                <path d="M4 12h112" />
                <path
                    d="M60 12c-8-8-18-9-26-6 8 4 17 6 26 6Zm0 0c8-8 18-9 26-6-8 4-17 6-26 6Z"
                />
            </svg>
            <p class="sc-g-welcome-text">{{ site.welcome }}</p>
        </section>

        <section class="sc-g-section">
            <h3 class="sc-g-heading">{{ site.programmeTitle }}</h3>
            <ol class="sc-g-vine">
                <li
                    v-for="(event, index) in site.events"
                    :key="event.time"
                    v-reveal="index * 120"
                    class="sc-g-stop"
                >
                    <svg
                        class="sc-g-leaf"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path d="M12 21C5 17 4 9 12 3c8 6 7 14 0 18Z" />
                    </svg>
                    <div class="sc-g-stop-text sc-rise">
                        <p
                            class="font-display text-lg font-semibold text-brand tabular-nums"
                        >
                            {{ event.time }}
                        </p>
                        <p class="font-semibold">{{ event.name }}</p>
                        <p class="text-sm text-muted-foreground">
                            {{ event.place }}
                        </p>
                    </div>
                </li>
            </ol>
        </section>

        <section class="sc-g-venue">
            <div v-reveal class="sc-wipe sc-g-arch">
                <ResponsivePhoto
                    :photo="site.photos.venue"
                    sizes="22rem"
                    class="sc-cover"
                />
            </div>
            <div v-reveal="150" class="sc-rise flex flex-col gap-2">
                <h3 class="sc-g-heading text-left">{{ site.travelTitle }}</h3>
                <p class="text-sm leading-relaxed text-muted-foreground">
                    {{ site.travel }}
                </p>
            </div>
        </section>

        <section class="sc-g-section">
            <h3 class="sc-g-heading">{{ site.galleryTitle }}</h3>
            <div class="flex justify-center gap-3 sm:gap-5">
                <span
                    v-for="(photo, index) in site.photos.gallery"
                    :key="photo.src"
                    v-reveal="index * 110"
                    class="sc-g-print"
                    :style="{ '--tilt': tilts[index] }"
                >
                    <ResponsivePhoto
                        :photo="photo"
                        sizes="12rem"
                        class="aspect-[4/5] w-full object-cover"
                    />
                </span>
            </div>
        </section>

        <section v-reveal class="sc-g-reply sc-rise">
            <p class="sc-g-heading">{{ site.replyTitle }}</p>
            <span class="showcase-action px-6 py-3 text-sm">{{
                site.action
            }}</span>
            <p class="caption">{{ site.replyBy }}</p>
        </section>
    </div>
</template>
