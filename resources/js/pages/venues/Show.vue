<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SiteHeader from '@/components/marketing/SiteHeader.vue';
import VenueEnquiry from '@/components/partners/VenueEnquiry.vue';
import { venuePage as copy } from '@/content/partners';

defineProps<{
    venue: {
        slug: string;
        name: string;
        town: string | null;
        address: string | null;
        postcode: string | null;
        locality: string | null;
        capacity: number | null;
        about: string | null;
        travel: string | null;
        stay: string | null;
        website: string | null;
        photos: { url: string; width: number; height: number }[];
    };
}>();

const sections = [
    { key: 'travel', title: copy.travel },
    { key: 'stay', title: copy.stay },
] as const;
</script>

<!--
    A Showcase venue's page, reached from the one credit line on a couple's
    site. The venue in its own words, and an opt-in request for a tour.
-->
<template>
    <Head
        :title="
            venue.locality
                ? `${venue.name} – Hochzeitslocation in ${venue.locality}`
                : `${venue.name} – Hochzeitslocation`
        "
    />
    <div class="legal-band stage">
        <SiteHeader />
        <div class="page-container relative pt-36 pb-16 sm:pt-44">
            <h1 class="display">{{ venue.name }}</h1>
            <p class="caption mt-4">
                {{ [venue.address, venue.town].filter(Boolean).join(', ') }}
                <template v-if="venue.capacity">
                    · {{ copy.capacity(venue.capacity) }}
                </template>
            </p>
        </div>
    </div>

    <div v-if="venue.photos.length" class="page-container venue-hero pt-12">
        <img
            v-for="(photo, index) in venue.photos.slice(0, 3)"
            :key="photo.url"
            :src="photo.url"
            :width="photo.width"
            :height="photo.height"
            :alt="index === 0 ? venue.name : ''"
            :loading="index === 0 ? 'eager' : 'lazy'"
            class="venue-hero-photo"
            :class="{ 'venue-hero-lead': index === 0 }"
        />
    </div>

    <div class="page-container venue-page">
        <article class="flex flex-col gap-10">
            <p v-if="venue.about" class="body-copy whitespace-pre-line">
                {{ venue.about }}
            </p>
            <template v-for="section in sections" :key="section.key">
                <section v-if="venue[section.key]" class="flex flex-col gap-3">
                    <h2 class="title">{{ section.title }}</h2>
                    <p class="body-copy whitespace-pre-line">
                        {{ venue[section.key] }}
                    </p>
                </section>
            </template>
            <a
                v-if="venue.website"
                :href="venue.website"
                target="_blank"
                rel="noopener"
                class="link-underline self-start"
                >{{ copy.website }}</a
            >
        </article>

        <VenueEnquiry :slug="venue.slug" :venue-name="venue.name" />
    </div>
</template>
