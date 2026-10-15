<script setup lang="ts">
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import { studio } from '@/content/landing';

const site = studio.site;
const [tall, ...pair] = site.photos.gallery;
</script>

<!--
    Atelier below the hero: a magazine. A pull-quote, the day as a ledger
    with large times, the venue wiping open under a caption card, an
    asymmetric photo grid, and a large close.
-->
<template>
    <div class="sc-a">
        <section v-reveal class="sc-a-quote sc-rise">
            <p class="sc-a-quote-text">
                {{ site.welcomeLead }}
                <span class="accent text-brand">{{ site.welcomeAccent }}</span>
                {{ site.welcomeTail }}
            </p>
            <p class="sc-a-quote-rest">{{ site.welcomeRest }}</p>
        </section>

        <section class="sc-a-section">
            <h3 class="sc-a-heading">{{ site.programmeTitle }}</h3>
            <ol>
                <li
                    v-for="(event, index) in site.events"
                    :key="event.time"
                    v-reveal="index * 80"
                    class="sc-a-row sc-rise"
                >
                    <span class="sc-a-time">{{ event.time }}</span>
                    <span class="sc-a-event">{{ event.name }}</span>
                    <span class="sc-a-place">{{ event.place }}</span>
                </li>
            </ol>
        </section>

        <section class="sc-a-venue">
            <div v-reveal class="sc-wipe">
                <ResponsivePhoto
                    :photo="site.photos.venue"
                    sizes="(min-width: 1024px) 60rem, 90vw"
                    class="sc-a-venue-photo"
                />
            </div>
            <div v-reveal="200" class="sc-a-caption sc-rise">
                <h3 class="sc-a-heading">{{ site.travelTitle }}</h3>
                <p class="text-sm leading-relaxed text-muted-foreground">
                    {{ site.travel }}
                </p>
            </div>
        </section>

        <section class="sc-a-section">
            <h3 class="sc-a-heading">{{ site.galleryTitle }}</h3>
            <div class="sc-a-grid">
                <div v-reveal class="sc-wipe sc-a-tile sc-a-tile-tall">
                    <ResponsivePhoto
                        :photo="tall"
                        sizes="30rem"
                        class="sc-cover"
                    />
                </div>
                <div
                    v-for="(photo, index) in pair"
                    :key="photo.src"
                    v-reveal="(index + 1) * 120"
                    class="sc-wipe sc-a-tile"
                >
                    <ResponsivePhoto
                        :photo="photo"
                        sizes="20rem"
                        class="sc-cover"
                    />
                </div>
            </div>
        </section>

        <section v-reveal class="sc-a-close sc-rise">
            <p class="sc-a-close-title">{{ site.replyTitle }}</p>
            <span class="showcase-action px-6 py-3 text-sm">{{
                site.action
            }}</span>
            <p class="caption">{{ site.replyBy }}</p>
        </section>
    </div>
</template>
