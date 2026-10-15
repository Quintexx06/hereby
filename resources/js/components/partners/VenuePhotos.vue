<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { ImagePlus, Trash2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import { Spinner } from '@/components/ui/spinner';
import { venuePhotos as copy } from '@/content/partners';
import { destroy, store } from '@/routes/partner/profile/photos';

const MAX = 6;

const props = defineProps<{ photos: { id: number; url: string }[] }>();

const page = usePage();
const uploading = ref(0);
const error = computed(() => page.props.errors?.photo ?? null);
const room = computed(() => MAX - props.photos.length);

/* One request per photo, so a weak connection loses one, not all. */
async function upload(event: Event): Promise<void> {
    const input = event.target as HTMLInputElement;
    const files = [...(input.files ?? [])].slice(0, room.value);
    uploading.value = files.length;
    for (const photo of files) {
        await new Promise<void>((resolve) =>
            router.post(
                store.url(),
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

function remove(id: number): void {
    router.delete(destroy.url(id), { preserveScroll: true });
}
</script>

<!-- A venue's photos: the first leads its page and every tour preview. -->
<template>
    <section class="flex flex-col gap-4" aria-labelledby="venue-photos">
        <div class="flex flex-col gap-1">
            <h2 id="venue-photos" class="app-section-title">
                {{ copy.title }}
            </h2>
            <p class="max-w-2xl text-sm text-muted-foreground">
                {{ copy.hint }}
            </p>
        </div>
        <ul class="venue-photos">
            <li v-for="photo in photos" :key="photo.id" class="venue-photo">
                <img :src="photo.url" alt="" class="size-full object-cover" />
                <button
                    type="button"
                    class="venue-photo-remove"
                    :aria-label="copy.remove"
                    @click="remove(photo.id)"
                >
                    <Trash2 class="size-4" />
                </button>
            </li>
            <li v-if="room > 0">
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
