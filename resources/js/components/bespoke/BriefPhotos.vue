<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { ImagePlus, Trash2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import { Spinner } from '@/components/ui/spinner';
import { bespoke } from '@/content/bespoke';
import { destroy, store } from '@/routes/weddings/bespoke/photos';

const MAX = 12;
const copy = bespoke.photos;

const props = defineProps<{
    weddingId: number;
    photos: { id: number; url: string }[];
}>();

const page = usePage();
const uploading = ref(0);
const error = computed(() => page.props.errors?.photo ?? null);
const room = computed(() => MAX - props.photos.length);

/* One request per image, so a weak connection loses one, not all. */
async function upload(event: Event): Promise<void> {
    const input = event.target as HTMLInputElement;
    const files = [...(input.files ?? [])].slice(0, room.value);
    uploading.value = files.length;
    for (const photo of files) {
        await new Promise<void>((resolve) =>
            router.post(
                store.url(props.weddingId),
                { photo },
                {
                    forceFormData: true,
                    preserveScroll: true,
                    onFinish: () => resolve(),
                },
            ),
        );
        uploading.value--;
    }
    input.value = '';
}
</script>

<!-- Inspiration for the team: up to twelve images, private to couple and team. -->
<template>
    <section class="flex flex-col gap-4" aria-labelledby="brief-photos">
        <div class="flex flex-col gap-1">
            <h2 id="brief-photos" class="app-section-title">
                {{ copy.title }}
            </h2>
            <p class="field-hint">{{ copy.hint }}</p>
        </div>
        <ul class="brief-photos">
            <li v-for="photo in photos" :key="photo.id" class="venue-photo">
                <img :src="photo.url" alt="" class="size-full object-cover" />
                <button
                    type="button"
                    class="venue-photo-remove"
                    :aria-label="copy.remove"
                    @click="
                        router.delete(destroy.url([weddingId, photo.id]), {
                            preserveScroll: true,
                        })
                    "
                >
                    <Trash2 class="size-4" />
                </button>
            </li>
            <li v-if="room > 0" :class="{ 'col-span-full': !photos.length }">
                <label class="couple-photo-add h-full">
                    <Spinner v-if="uploading" />
                    <ImagePlus v-else class="size-5" aria-hidden="true" />
                    <span class="font-medium">{{
                        uploading ? copy.uploading : copy.add(room)
                    }}</span>
                    <input
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        multiple
                        class="sr-only"
                        :disabled="uploading > 0"
                        @change="upload"
                    />
                </label>
            </li>
        </ul>
        <p v-if="error" class="text-sm text-destructive">{{ error }}</p>
    </section>
</template>
