<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import ConfirmButton from '@/components/guests/editor/ConfirmButton.vue';
import { memoriesPage as copy } from '@/content/memories';
import { destroy } from '@/routes/weddings/memories';
import type { GalleryPhoto } from '@/types';

/* The gallery: small copies in a grid; a tap opens the photo as it was sent. */
const props = defineProps<{ weddingId: number; photos: GalleryPhoto[] }>();

function remove(photo: GalleryPhoto): void {
    router.delete(destroy.url([props.weddingId, photo.id]), {
        preserveScroll: true,
    });
}
</script>

<template>
    <ul class="memories-grid">
        <li v-for="photo in photos" :key="photo.id" class="flex flex-col gap-2">
            <a
                :href="photo.original"
                target="_blank"
                rel="noopener"
                class="memories-photo"
                :aria-label="copy.open"
            >
                <img :src="photo.thumbnail" alt="" loading="lazy" />
            </a>
            <ConfirmButton
                :label="copy.remove"
                :question="copy.removeConfirm"
                @confirm="remove(photo)"
            />
        </li>
    </ul>
</template>
