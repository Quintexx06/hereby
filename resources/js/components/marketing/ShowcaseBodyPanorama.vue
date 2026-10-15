<script setup lang="ts">
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import { studio } from '@/content/landing';

const site = studio.site;
</script>

<!--
    Panorama below the hero: cinematic. One giant line, the day as film
    frames on a dark band, the venue in 21:9 with a slow push-in, a
    filmstrip of photos, and the reply running past as a band.
-->
<template>
    <div class="sc-p">
        <section v-reveal class="sc-p-welcome sc-rise">
            <p class="sc-p-welcome-text">
                {{ site.welcomeLead }}
                <span class="accent text-brand">{{ site.welcomeAccent }}</span>
                {{ site.welcomeTail }}
            </p>
            <p class="max-w-md text-muted-foreground">{{ site.welcomeRest }}</p>
        </section>

        <section class="sc-p-band">
            <h3 class="sc-p-heading">{{ site.programmeTitle }}</h3>
            <ol class="sc-p-frames">
                <li
                    v-for="(event, index) in site.events"
                    :key="event.time"
                    v-reveal="index * 90"
                    class="sc-p-frame sc-slide"
                >
                    <span class="sc-p-frame-time">{{ event.time }}</span>
                    <span class="font-semibold">{{ event.name }}</span>
                    <span class="text-sm opacity-70">{{ event.place }}</span>
                </li>
            </ol>
        </section>

        <section v-reveal class="sc-p-venue">
            <ResponsivePhoto
                :photo="site.photos.venue"
                sizes="(min-width: 1024px) 64rem, 94vw"
                class="sc-p-venue-photo"
            />
            <div class="sc-p-venue-text">
                <h3 class="sc-p-heading">{{ site.travelTitle }}</h3>
                <p class="max-w-sm text-sm leading-relaxed">
                    {{ site.travel }}
                </p>
            </div>
        </section>

        <section class="sc-p-band">
            <h3 class="sc-p-heading">{{ site.galleryTitle }}</h3>
            <div class="sc-p-film">
                <div
                    v-for="(photo, index) in site.photos.gallery"
                    :key="photo.src"
                    v-reveal="index * 100"
                    class="sc-p-film-cell sc-slide"
                >
                    <ResponsivePhoto
                        :photo="photo"
                        sizes="16rem"
                        class="sc-cover"
                    />
                </div>
            </div>
        </section>

        <section class="sc-p-reply">
            <div class="sc-p-run" aria-hidden="true">
                <span v-for="n in 6" :key="n">{{ site.replyTitle }} ·</span>
            </div>
            <div class="flex flex-col items-center gap-3 pt-6">
                <span class="showcase-action px-6 py-3 text-sm">{{
                    site.action
                }}</span>
                <p class="text-xs opacity-70">{{ site.replyBy }}</p>
            </div>
        </section>
    </div>
</template>
