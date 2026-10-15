<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import SiteHeader from '@/components/marketing/SiteHeader.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { venuePage as copy } from '@/content/partners';
import { enquiry } from '@/routes/venues';

const props = defineProps<{
    venue: {
        slug: string;
        name: string;
        town: string | null;
        address: string | null;
        capacity: number | null;
        about: string | null;
        travel: string | null;
        stay: string | null;
        website: string | null;
    };
}>();

const form = useForm({ name: '', email: '', preferred_date: '', message: '' });

function send(): void {
    form.post(enquiry.url(props.venue.slug), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

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
    <Head :title="venue.name" />
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

        <form class="venue-enquiry" @submit.prevent="send">
            <h2 class="title">{{ copy.ask }}</h2>
            <p class="text-muted-foreground">{{ copy.askLede }}</p>
            <div class="grid gap-2">
                <label for="enquiry-name" class="field-label">{{
                    copy.name
                }}</label>
                <Input id="enquiry-name" v-model="form.name" required />
                <InputError :message="form.errors.name" />
            </div>
            <div class="grid gap-2">
                <label for="enquiry-email" class="field-label">{{
                    copy.email
                }}</label>
                <Input
                    id="enquiry-email"
                    v-model="form.email"
                    type="email"
                    required
                />
                <InputError :message="form.errors.email" />
            </div>
            <div class="grid gap-2">
                <label for="enquiry-date" class="field-label">{{
                    copy.date
                }}</label>
                <Input
                    id="enquiry-date"
                    v-model="form.preferred_date"
                    type="date"
                />
                <InputError :message="form.errors.preferred_date" />
            </div>
            <div class="grid gap-2">
                <label for="enquiry-message" class="field-label">{{
                    copy.message
                }}</label>
                <textarea
                    id="enquiry-message"
                    v-model="form.message"
                    rows="4"
                    class="textarea"
                />
            </div>
            <Button type="submit" size="pill" :disabled="form.processing">
                {{ copy.send }}
            </Button>
            <p class="caption">{{ copy.privacy }}</p>
        </form>
    </div>
</template>
