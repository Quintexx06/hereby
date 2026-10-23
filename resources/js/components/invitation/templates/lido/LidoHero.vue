<script setup lang="ts">
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import LidoWaves from '@/components/invitation/templates/lido/LidoWaves.vue';
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
    Lido opens like a summer afternoon at the lake bath: the striped
    awning with its scalloped edge, a butter field, and the couple's photo
    cut out as a sun going down into the lake in stripes, the water
    drifting below; their names in Shrikhand over it.
-->
<template>
    <header class="lido-hero">
        <div class="lido-awning" aria-hidden="true" />
        <div class="lido-hero-stage">
            <div class="lido-sun" :data-photo="cover !== null">
                <HeroPhoto
                    v-if="cover"
                    :photo="cover"
                    :alt="alt"
                    eager
                    class="lido-sun-photo"
                />
            </div>
            <LidoWaves class="lido-hero-waves" />
            <div class="lido-hero-copy">
                <h1 class="lido-names">
                    <span>{{ names.first }}</span>
                    <span v-if="names.second"
                        ><span class="lido-amp">&amp;</span>
                        {{ names.second }}</span
                    >
                    <span class="sr-only">{{ t('invitation.marry') }}</span>
                </h1>
                <p class="lido-when">
                    <span class="lido-when-day"
                        >{{ date.weekday }}, {{ date.long }}</span
                    >
                    <span v-if="place">{{ place }}</span>
                </p>
            </div>
        </div>
    </header>
</template>
