<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import SiteHeader from '@/components/marketing/SiteHeader.vue';
import { Button } from '@/components/ui/button';
import { topics as copy } from '@/content/landing';
import { landingPhotos } from '@/content/landing-photos';
import { home, register } from '@/routes';

const props = defineProps<{
    label: string;
    title: string;
    lede: string;
    photo: string;
}>();

const picture =
    landingPhotos[props.photo as keyof typeof landingPhotos] ??
    landingPhotos.heroVeil;
</script>

<!-- A topic page opens on night: the promise and one action, beside a photo. -->
<template>
    <div class="legal-band stage">
        <SiteHeader />
        <div class="page-container topic-hero">
            <div class="topic-hero-copy">
                <nav aria-label="Brotkrumen" class="caption">
                    <Link :href="home()" class="link-underline">Hereby</Link>
                    <span aria-hidden="true"> / </span>
                    <span>{{ label }}</span>
                </nav>
                <h1 class="display max-w-[18ch] text-balance">{{ title }}</h1>
                <p class="topic-lede">{{ lede }}</p>
                <div class="flex flex-wrap items-center gap-6">
                    <Button as-child size="pill">
                        <Link :href="register()">{{ copy.cta }}</Link>
                    </Button>
                    <Link :href="`${home().url}#studio`" class="link-underline">
                        {{ copy.examples }}
                    </Link>
                </div>
            </div>
            <figure class="topic-hero-photo">
                <ResponsivePhoto
                    :photo="picture"
                    eager
                    sizes="(min-width: 1024px) 40vw, 100vw"
                    class="size-full object-cover"
                />
            </figure>
        </div>
    </div>
</template>
