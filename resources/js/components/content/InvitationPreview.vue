<script setup lang="ts">
import { ref, watch } from 'vue';
import LanguageTabs from '@/components/content/LanguageTabs.vue';
import { contentPage as copy } from '@/content/content';
import { preview } from '@/routes/weddings';
import type { ContentWedding, Locale } from '@/types';

/*
 * The invitation as a guest sees it on a phone, beside the editor on wide
 * screens. It shows what is saved: `version` changes after every save or
 * reorder and loads it again, at the spot the couple had scrolled to.
 */
const props = defineProps<{ wedding: ContentWedding; version: string }>();
const language = ref<Locale>(props.wedding.languages[0]);
const frame = ref<HTMLIFrameElement | null>(null);
let scrolledTo = 0;

watch(
    () => [language.value, props.version],
    () => {
        scrolledTo = frame.value?.contentWindow?.scrollY ?? 0;
    },
    { flush: 'pre' },
);

function restoreScroll(): void {
    frame.value?.contentWindow?.scrollTo(0, scrolledTo);
}
</script>

<template>
    <aside class="content-preview" :aria-label="copy.previewLabel">
        <div class="content-preview-phone">
            <div class="content-preview-screen">
                <iframe
                    ref="frame"
                    :key="`${language}-${version}`"
                    :src="
                        preview.url(wedding.id, {
                            query: { sprache: language, einbetten: 1 },
                        })
                    "
                    :title="copy.previewLabel"
                    loading="lazy"
                    class="content-preview-frame"
                    @load="restoreScroll"
                />
                <span class="content-preview-island" aria-hidden="true" />
                <span class="content-preview-home" aria-hidden="true" />
            </div>
        </div>
        <LanguageTabs
            v-model="language"
            :languages="wedding.languages"
            :filled="() => true"
        />
        <p class="field-hint max-w-72 text-center">{{ copy.previewHint }}</p>
    </aside>
</template>
