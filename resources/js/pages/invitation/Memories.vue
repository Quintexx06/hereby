<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Check, ImagePlus } from '@lucide/vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import { usePhotoUpload } from '@/composables/usePhotoUpload';
import { useTrans } from '@/composables/useTrans';
import type { WeddingTemplate } from '@/types';

const props = defineProps<{
    wedding: {
        coupleNames: string;
        template: WeddingTemplate;
    };
    uploadUrl: string;
}>();

const { t, tc, localeTag } = useTrans();
const { sending, total, done, sent, failed, send, retry } = usePhotoUpload(
    props.uploadUrl,
);

function pick(event: Event): void {
    const input = event.target as HTMLInputElement;
    void send([...(input.files ?? [])]);
    input.value = '';
}
</script>

<!--
    «Fotos von allen»: one screen on the phone. Pick photos, they go. No
    account, no app, nothing to fill in (product rule 1).
-->
<template>
    <WeddingThemeScope :template="wedding.template" :lang="localeTag()">
        <Head :title="t('memories.title', { couple: wedding.coupleNames })" />
        <main class="reply-page">
            <header class="flex flex-col gap-3">
                <p class="caption">{{ wedding.coupleNames }}</p>
                <h1 class="headline">
                    {{ t('memories.title', { couple: wedding.coupleNames }) }}
                </h1>
                <p class="text-muted-foreground">{{ t('memories.lede') }}</p>
            </header>

            <div class="flex flex-col gap-4" aria-live="polite">
                <p v-if="sending" role="status">
                    {{
                        t('memories.sending', {
                            done: Math.min(done + 1, total),
                            total,
                        })
                    }}
                </p>
                <p v-else-if="sent > 0" class="reply-summary" role="status">
                    <Check class="size-5" aria-hidden="true" />
                    {{ tc('memories.sent', sent) }}
                </p>

                <div
                    v-if="!sending && failed.length"
                    class="flex flex-col items-start gap-3"
                >
                    <p class="text-destructive" role="alert">
                        {{ tc('memories.failed', failed.length) }}
                        {{ t('memories.invalid') }}
                    </p>
                    <button type="button" class="reply-send" @click="retry">
                        {{ t('memories.retry') }}
                    </button>
                </div>
            </div>

            <label
                class="reply-send cursor-pointer self-start focus-within:ring-2 focus-within:ring-ring"
                :class="{ 'pointer-events-none opacity-40': sending }"
            >
                <ImagePlus class="size-4" aria-hidden="true" />
                {{
                    sent > 0 ? t('memories.choose_more') : t('memories.choose')
                }}
                <input
                    type="file"
                    class="sr-only"
                    accept="image/jpeg,image/png,image/webp"
                    multiple
                    :disabled="sending"
                    @change="pick"
                />
            </label>
        </main>
    </WeddingThemeScope>
</template>
