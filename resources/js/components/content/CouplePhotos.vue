<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { ImagePlus } from '@lucide/vue';
import { computed, ref } from 'vue';
import CouplePhotoTile from '@/components/content/CouplePhotoTile.vue';
import { Spinner } from '@/components/ui/spinner';
import { couplePhotosCopy as copy } from '@/content/content';
import { store } from '@/routes/weddings/photos';
import type { CouplePhoto } from '@/types';

const MAX = 4;

const props = defineProps<{ weddingId: number; photos: CouplePhoto[] }>();
const emit = defineEmits<{ changed: [] }>();

const page = usePage();
const input = ref<HTMLInputElement | null>(null);
const uploading = ref(0);
const error = computed(() => page.props.errors?.photo ?? null);
const room = computed(() => MAX - props.photos.length);

/* One request per photo, so a weak connection loses one, not all. */
async function upload(event: Event): Promise<void> {
    const files = [...((event.target as HTMLInputElement).files ?? [])].slice(
        0,
        room.value,
    );
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
    if (input.value) {
        input.value.value = '';
    }
    emit('changed');
}
</script>

<!-- The couple's own photos for the top of their site: up to four, first is the cover. -->
<template>
    <section class="flex flex-col gap-4" aria-labelledby="couple-photos">
        <div class="flex flex-col gap-1">
            <h2 id="couple-photos" class="app-section-title">
                {{ copy.title }}
            </h2>
            <p class="max-w-2xl text-sm text-muted-foreground">
                {{ copy.hint }}
            </p>
        </div>
        <ul class="couple-photos">
            <CouplePhotoTile
                v-for="(photo, index) in photos"
                :key="photo.id"
                :wedding-id="weddingId"
                :photo="photo"
                :index="index"
                @changed="emit('changed')"
            />
            <li
                v-if="room > 0"
                class="couple-photo-room"
                :style="{ '--room': room }"
            >
                <label class="couple-photo-add">
                    <span class="couple-photo-add-icon">
                        <Spinner v-if="uploading" />
                        <ImagePlus v-else class="size-5" aria-hidden="true" />
                    </span>
                    <span class="flex flex-col gap-0.5">
                        <span class="font-medium">
                            {{ uploading ? copy.uploading : copy.add }}
                        </span>
                        <span class="text-sm text-muted-foreground">
                            {{ copy.room(room) }}
                        </span>
                    </span>
                    <input
                        ref="input"
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
