<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import SiteHeader from '@/components/marketing/SiteHeader.vue';
import { Button } from '@/components/ui/button';
import { useVeilCurtain } from '@/composables/motion/useVeilCurtain';
import { hero } from '@/content/landing';
import { register } from '@/routes';

const canvas = ref<HTMLCanvasElement | null>(null);
const photo = ref<HTMLImageElement | null>(null);
const { state, revealed, usesWebgl, skip } = useVeilCurtain(canvas, photo);
</script>

<!--
    Signature moment: a sheer veil parts and reveals the couple.
    The photo is a plain <img> (LCP, alt text); the veil is decoration.
-->
<template>
    <section
        class="hero stage"
        :data-curtain="state"
        :data-webgl="usesWebgl"
        :data-revealed="revealed"
        @click="skip"
    >
        <SiteHeader />

        <ResponsivePhoto
            v-model:image="photo"
            :photo="hero.photo"
            eager
            class="hero-photo"
        />
        <canvas ref="canvas" class="hero-canvas" aria-hidden="true" />
        <div class="veil-panel veil-panel-left" aria-hidden="true" />
        <div class="veil-panel veil-panel-right" aria-hidden="true" />
        <div class="hero-scrim" aria-hidden="true" />

        <div class="page-container hero-content">
            <h1 class="flex flex-col gap-8">
                <span class="display-hero hero-reveal">
                    {{ hero.titleLead }}<br />
                    <span class="text-brand">{{ hero.titleTail }}&nbsp;</span>
                    <span class="hero-yes text-brand"
                        >{{ hero.titleYes }}
                        <svg
                            class="hero-swoosh"
                            viewBox="0 0 220 44"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                pathLength="1"
                                d="M6 26 C 48 13, 118 9, 196 15 C 211 16.5, 210 25, 197 25.5 C 186 26, 184 19.5, 196 18"
                            />
                            <path
                                pathLength="1"
                                d="M22 36 C 70 29, 132 27, 184 31"
                            /></svg
                    ></span>
                </span>
                <span class="hero-statement hero-reveal" style="--delay: 160ms">
                    {{ hero.statement }}
                </span>
            </h1>

            <div
                class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between"
            >
                <p class="lede hero-reveal" style="--delay: 300ms">
                    {{ hero.lede }}
                </p>
                <div
                    class="hero-reveal flex flex-wrap items-center gap-3"
                    style="--delay: 420ms"
                >
                    <Button as-child size="pill">
                        <Link :href="register()">{{ hero.primaryCta }}</Link>
                    </Button>
                    <Button
                        as-child
                        variant="outline"
                        size="pill"
                        class="border-[1.5px] border-foreground bg-transparent text-foreground hover:bg-foreground hover:text-background dark:border-foreground dark:bg-transparent dark:hover:bg-foreground"
                    >
                        <a href="#ablauf">{{ hero.secondaryCta }}</a>
                    </Button>
                </div>
            </div>
        </div>

        <button type="button" class="hero-skip" @click.stop="skip">
            {{ hero.skip }}
        </button>
    </section>
</template>
