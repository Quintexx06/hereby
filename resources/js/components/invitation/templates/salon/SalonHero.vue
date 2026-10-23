<script setup lang="ts">
import { computed } from 'vue';
import SalonFrame from '@/components/invitation/templates/salon/SalonFrame.vue';
import { useTrans } from '@/composables/useTrans';
import type { WeddingPhoto } from '@/types';

const props = defineProps<{
    names: { first: string; second: string };
    date: { weekday: string; long: string };
    place: string | null;
    cover: WeddingPhoto | null;
    alt: string;
}>();
const { t } = useTrans();

const initials = computed(() =>
    [props.names.first, props.names.second]
        .filter(Boolean)
        .map((name) => name.trim().charAt(0))
        .join(' & '),
);
</script>

<!--
    Salon opens on the claret wall of a grand hotel's salon at night: the
    couple's photo in a gilt frame, hung from the ampersand on two cords
    and lit from above, their names in Ballet on either side, and a brass
    plaque under it with the day and the place.
-->
<template>
    <header class="salon-hero">
        <h1 class="salon-names">
            <span class="salon-name" data-side="left">{{ names.first }}</span>
            <span class="sr-only">&amp;</span>
            <span v-if="names.second" class="salon-name" data-side="right">{{
                names.second
            }}</span>
            <span class="sr-only">{{ t('invitation.marry') }}</span>
        </h1>
        <div class="salon-hanging">
            <span class="salon-nail" aria-hidden="true">&amp;</span>
            <svg
                class="salon-cord"
                viewBox="0 0 100 40"
                preserveAspectRatio="none"
                aria-hidden="true"
            >
                <path d="M50 2 L10 40 M50 2 L90 40" />
            </svg>
            <SalonFrame
                :photo="cover"
                :alt="alt"
                :initials="initials"
                eager
                class="salon-hero-frame"
            />
        </div>
        <p class="salon-plaque">
            <span>{{ date.weekday }}, {{ date.long }}</span>
            <span v-if="place">{{ place }}</span>
        </p>
    </header>
</template>
