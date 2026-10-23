<script setup lang="ts">
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import ArveTopo from '@/components/invitation/templates/arve/ArveTopo.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingPhoto } from '@/types';

defineProps<{
    names: { first: string; second: string };
    date: { weekday: string; long: string; daysToGo: number };
    place: string | null;
    cover: WeddingPhoto | null;
    alt: string;
}>();
const { t, tc } = useTrans();
</script>

<!--
    Arve opens on the landscape, full height: the contour lines of a Swiss
    map drawing themselves over the sky, the couple's names across the foot
    in black condensed capitals like a ski poster, and a yellow trail sign
    pointing to the day.
-->
<template>
    <header class="arve-hero" :data-photo="cover !== null">
        <div class="arve-hero-land">
            <HeroPhoto
                v-if="cover"
                :photo="cover"
                :alt="alt"
                eager
                class="arve-hero-photo"
            />
            <ArveTopo class="arve-hero-topo" />
        </div>
        <div class="arve-hero-copy">
            <p class="arve-sign arve-hero-sign">
                <span class="arve-sign-to">{{ place ?? date.long }}</span>
                <span class="arve-sign-time">{{
                    tc('invitation.countdown', date.daysToGo)
                }}</span>
            </p>
            <h1 class="arve-names">
                <span>{{ names.first }}</span>
                <span v-if="names.second"
                    ><span class="arve-amp">&amp;</span>
                    {{ names.second }}</span
                >
                <span class="sr-only">{{ t('invitation.marry') }}</span>
            </h1>
            <p class="arve-when">
                <span class="arve-when-day">{{ date.long }}</span>
                <span>{{ date.weekday }}</span>
            </p>
        </div>
    </header>
</template>
