<script setup lang="ts">
import StudioCarousel from '@/components/marketing/StudioCarousel.vue';
import { studio } from '@/content/landing';
</script>

<!--
    The five designs (ADR 0015, 0016) right after the hero, as their real
    first screens on a computer and a phone, in a carousel that loops, and
    the way to each whole sample site.
-->
<template>
    <section id="studio" class="overflow-hidden">
        <div class="page-container section flex flex-col gap-10 sm:gap-12">
            <header v-reveal class="flex max-w-3xl reveal flex-col gap-6">
                <h2 class="headline">{{ studio.title }}</h2>
                <p class="lede">{{ studio.lede }}</p>
            </header>
            <StudioCarousel />
        </div>
    </section>
</template>
