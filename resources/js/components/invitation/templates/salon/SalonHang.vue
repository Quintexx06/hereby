<script setup lang="ts">
import { computed } from 'vue';
import SalonFrame from '@/components/invitation/templates/salon/SalonFrame.vue';
import type { WeddingPhoto } from '@/types';

const props = defineProps<{
    photos: WeddingPhoto[];
    alt: string;
    from: number;
}>();

const shown = computed(() => props.photos.slice(props.from));
</script>

<!--
    The couple's photos hung salon-style, as in the Petersburg hang of a
    grand hotel: gilt frames of different sizes close together on the
    wall. Each frame settles on its nail as it comes into view.
-->
<template>
    <section v-if="shown.length" class="salon-hang" :data-count="shown.length">
        <SalonFrame
            v-for="photo in shown"
            :key="photo.url"
            :photo="photo"
            :alt="alt"
            class="salon-hang-frame"
        />
    </section>
</template>
