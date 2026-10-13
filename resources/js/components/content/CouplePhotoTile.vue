<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Star, Trash2 } from '@lucide/vue';
import { couplePhotosCopy as copy } from '@/content/content';
import { cover, destroy, focus } from '@/routes/weddings/photos';
import type { CouplePhoto } from '@/types';

const props = defineProps<{
    weddingId: number;
    photo: CouplePhoto;
    index: number;
}>();
const emit = defineEmits<{ changed: [] }>();

const done = { preserveScroll: true, onSuccess: () => emit('changed') };

/* A tap marks the spot that every template keeps in view when it crops. */
function setFocus(event: MouseEvent): void {
    const box = (event.currentTarget as HTMLElement).getBoundingClientRect();
    const percent = (value: number) =>
        Math.round(Math.min(Math.max(value * 100, 0), 100));
    router.put(
        focus.url([props.weddingId, props.photo.id]),
        {
            x: percent((event.clientX - box.left) / box.width),
            y: percent((event.clientY - box.top) / box.height),
        },
        done,
    );
}

function act(request: { url: string; method: string }): void {
    router.visit(request.url, {
        method: request.method as 'put' | 'delete',
        ...done,
    });
}
</script>

<!-- One couple photo: tap to set its focus, make it the cover, or remove it. -->
<template>
    <li class="couple-photo">
        <button
            type="button"
            class="couple-photo-focus"
            :aria-label="`${copy.focus}: ${copy.alt(index)}`"
            @click="setFocus"
        >
            <img
                :src="photo.url"
                :alt="copy.alt(index)"
                class="size-full object-cover"
                :style="{
                    objectPosition: `${photo.focus[0]}% ${photo.focus[1]}%`,
                }"
                loading="lazy"
            />
            <span
                class="couple-photo-dot"
                :style="{
                    left: `${photo.focus[0]}%`,
                    top: `${photo.focus[1]}%`,
                }"
                aria-hidden="true"
            />
        </button>
        <span v-if="index === 0" class="couple-photo-badge">
            <Star class="size-3" aria-hidden="true" /> {{ copy.cover }}
        </span>
        <div class="couple-photo-actions">
            <button
                v-if="index > 0"
                type="button"
                class="couple-photo-action"
                @click="act(cover([weddingId, photo.id]))"
            >
                <Star class="size-4" aria-hidden="true" />
                {{ copy.makeCover }}
            </button>
            <button
                type="button"
                class="couple-photo-action"
                :aria-label="copy.remove"
                @click="act(destroy([weddingId, photo.id]))"
            >
                <Trash2 class="size-4" aria-hidden="true" />
            </button>
        </div>
    </li>
</template>
