<script setup lang="ts">
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingPhoto } from '@/types';

defineProps<{
    names: { first: string; second: string };
    date: { long: string };
    place: string | null;
    cover: WeddingPhoto | null;
    alt: string;
}>();
const { t } = useTrans();
</script>

<!--
    Arve opens on the landscape: the couple's photo as a wide panorama with
    nothing written over it, then their names below in Vollkorn's black on
    wool-white paper, and one line with the place and the date.
-->
<template>
    <header class="arve-hero">
        <figure v-if="cover" class="arve-panorama">
            <HeroPhoto :photo="cover" :alt="alt" eager class="size-full" />
        </figure>
        <div class="arve-title">
            <h1 class="arve-names">
                {{ names.first }}
                <template v-if="names.second">
                    <span class="arve-amp">&amp;</span> {{ names.second }}
                </template>
                <span class="sr-only">{{ t('invitation.marry') }}</span>
            </h1>
            <p class="arve-line">
                <template v-if="place">{{ place }} · </template>{{ date.long }}
            </p>
        </div>
    </header>
</template>
