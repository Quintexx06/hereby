<script setup lang="ts">
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingPhoto } from '@/types';

defineProps<{
    names: { first: string; second: string };
    date: { weekday: string; long: string };
    place: string | null;
    cover: WeddingPhoto | null;
    alt: string;
}>();
const { t } = useTrans();
</script>

<!--
    Garten opens like the title page of a private-press book: the couple's
    photo tipped in as a plate with wide paper margins, then their names in
    Linden Hill's italic with a fig ampersand, the date and the place.
-->
<template>
    <header class="garten-hero">
        <figure v-if="cover" class="garten-plate">
            <HeroPhoto :photo="cover" :alt="alt" eager class="size-full" />
        </figure>
        <h1 class="garten-names">
            <span>{{ names.first }}</span>
            <template v-if="names.second">
                <span class="garten-amp">&amp;</span>
                <span>{{ names.second }}</span>
            </template>
            <span class="sr-only">{{ t('invitation.marry') }}</span>
        </h1>
        <p class="garten-date">{{ date.weekday }}, {{ date.long }}</p>
        <p v-if="place" class="garten-place">{{ place }}</p>
    </header>
</template>
