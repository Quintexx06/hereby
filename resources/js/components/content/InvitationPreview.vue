<script setup lang="ts">
import { ref } from 'vue';
import LanguageTabs from '@/components/content/LanguageTabs.vue';
import { contentPage as copy } from '@/content/content';
import { preview } from '@/routes/weddings';
import type { ContentWedding, Locale } from '@/types';

/*
 * The invitation as a guest sees it on a phone, beside the editor on wide
 * screens. It shows what is saved: `version` changes after every save and
 * loads it again.
 */
const props = defineProps<{ wedding: ContentWedding; version: number }>();
const language = ref<Locale>(props.wedding.languages[0]);
</script>

<template>
    <aside class="content-preview" :aria-label="copy.previewLabel">
        <div class="flex flex-col gap-1">
            <h2 class="app-section-title">{{ copy.previewLabel }}</h2>
            <p class="field-hint">{{ copy.previewHint }}</p>
        </div>
        <LanguageTabs
            v-model="language"
            :languages="wedding.languages"
            :filled="() => true"
        />
        <div class="content-preview-phone">
            <iframe
                :key="`${language}-${version}`"
                :src="preview.url(wedding.id, { query: { sprache: language } })"
                :title="copy.previewLabel"
                loading="lazy"
                class="content-preview-frame"
            />
        </div>
    </aside>
</template>
