<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import HerebyWordmark from '@/components/brand/HerebyWordmark.vue';
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import { landingPhotos } from '@/content/landing-photos';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();

const words = ['Vorhang', 'auf', 'für', 'euer', 'Ja.'];
</script>

<!--
    Sign in / sign up: the landing page's stage on the left (the veil draws
    open over a slowly settling photo, the line rises word by word), the
    form on the right with each field arriving in turn.
-->
<template>
    <div class="auth surface-light">
        <aside class="auth-stage stage" aria-hidden="true">
            <ResponsivePhoto
                :photo="landingPhotos.veilKiss"
                eager
                sizes="50vw"
                class="auth-photo"
            />
            <div class="veil-panel veil-panel-left auth-veil" />
            <div class="veil-panel veil-panel-right auth-veil" />
            <div class="auth-scrim" />

            <Link :href="home()" class="relative z-10" tabindex="-1">
                <HerebyWordmark />
            </Link>
            <p class="auth-line">
                <span
                    v-for="(word, index) in words"
                    :key="word"
                    class="auth-word"
                    :class="{ 'text-brand': index > 1 }"
                    :style="{ '--i': index }"
                    >{{ word }}</span
                >
            </p>
        </aside>

        <main class="auth-panel">
            <div class="auth-column">
                <Link
                    :href="home()"
                    class="auth-rise self-start"
                    style="--i: 0"
                >
                    <AppLogoIcon class="size-10 text-foreground" />
                    <span class="sr-only">Hereby</span>
                </Link>
                <div class="auth-rise flex flex-col gap-2" style="--i: 1">
                    <h1 class="headline text-[clamp(2rem,3vw,2.75rem)]">
                        {{ title }}
                    </h1>
                    <p class="text-muted-foreground">{{ description }}</p>
                </div>
                <div class="auth-fields">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>
