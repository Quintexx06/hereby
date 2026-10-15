<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Check, ImagePlus } from '@lucide/vue';
import PageMasthead from '@/components/PageMasthead.vue';
import { partnerUpload as copy } from '@/content/partners';
import { usePhotoUpload } from '@/composables/usePhotoUpload';
import { calendar } from '@/routes/partner';

const props = defineProps<{
    wedding: { id: number; couple_names: string };
    uploaded: number;
    uploadUrl: string;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Hochzeiten', href: calendar() },
            { title: 'Fotos', href: '' },
        ],
    },
});

const { sending, total, done, sent, failed, send, retry } = usePhotoUpload(
    props.uploadUrl,
);

function pick(event: Event): void {
    const input = event.target as HTMLInputElement;
    void send([...(input.files ?? [])]);
    input.value = '';
}
</script>

<!-- Gallery delivery: the photographer's photos into the couple's memories. -->
<template>
    <Head :title="copy.title(wedding.couple_names)" />

    <div class="app-page gap-8">
        <PageMasthead
            :title="copy.title(wedding.couple_names)"
            :lede="copy.lede"
        />

        <div class="flex flex-col gap-4" aria-live="polite">
            <p class="caption">{{ copy.already(uploaded + sent) }}</p>
            <p v-if="sending" role="status">
                {{ copy.sending(Math.min(done + 1, total), total) }}
            </p>
            <p v-else-if="sent > 0" class="reply-summary" role="status">
                <Check class="size-5" aria-hidden="true" />
                {{ copy.sent(sent) }}
            </p>
            <div
                v-if="!sending && failed.length"
                class="flex flex-col items-start gap-3"
            >
                <p class="text-destructive" role="alert">
                    {{ copy.failed(failed.length) }}
                </p>
                <button type="button" class="witness-action" @click="retry">
                    {{ copy.retry }}
                </button>
            </div>
        </div>

        <label class="partner-upload" :data-busy="sending">
            <ImagePlus class="size-5" aria-hidden="true" />
            {{ copy.pick }}
            <input
                type="file"
                class="sr-only"
                accept="image/jpeg,image/png,image/webp"
                multiple
                :disabled="sending"
                @change="pick"
            />
        </label>
    </div>
</template>
