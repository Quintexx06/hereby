<script setup lang="ts">
import { languages as names } from '@/content/setup';
import type { Locale } from '@/types';

/* One tab per wedding language; a dot marks languages still empty. */
defineProps<{ languages: Locale[]; filled: (locale: Locale) => boolean }>();
const current = defineModel<Locale>({ required: true });
</script>

<template>
    <div v-if="languages.length > 1" class="segmented self-start" role="group">
        <button
            v-for="locale in languages"
            :key="locale"
            type="button"
            class="segmented-option"
            :aria-pressed="current === locale"
            @click="current = locale"
        >
            {{ names[locale] }}
            <template v-if="!filled(locale)">
                <span class="sr-only">(leer)</span>
                <span
                    class="size-1.5 rounded-full bg-current opacity-40"
                    aria-hidden="true"
                />
            </template>
        </button>
    </div>
</template>
