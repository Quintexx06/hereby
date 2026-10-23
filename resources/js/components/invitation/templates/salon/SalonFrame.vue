<script setup lang="ts">
import HeroPhoto from '@/components/invitation/HeroPhoto.vue';
import type { WeddingPhoto } from '@/types';

const { eager = false } = defineProps<{
    photo: WeddingPhoto | null;
    alt: string;
    /** Without a photo the frame holds the couple's initials on velvet. */
    initials?: string;
    eager?: boolean;
}>();
</script>

<!--
    A gilt frame as in a grand hotel's salon: the moulding, a dark liner,
    then the photo. Every photo on a Salon page hangs in one.
-->
<template>
    <figure class="salon-frame">
        <div class="salon-frame-liner">
            <HeroPhoto
                v-if="photo"
                :photo="photo"
                :alt="alt"
                :eager="eager"
                class="salon-frame-photo"
            />
            <span v-else class="salon-frame-initials" aria-hidden="true">{{
                initials
            }}</span>
        </div>
    </figure>
</template>
