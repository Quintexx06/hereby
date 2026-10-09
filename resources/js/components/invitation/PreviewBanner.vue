<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { previewCopy } from '@/content/content';
import { languages as languageNames } from '@/content/setup';
import { dashboard } from '@/routes';
import type { Locale } from '@/types';

/* Couple-only: the preview is in the guests' language, this bar stays German. */
defineProps<{ languages: string[] }>();
const page = usePage();
const current = () => page.props.locale;
const withLanguage = (locale: string) =>
    `${window.location.pathname}?sprache=${locale}`;
</script>

<template>
    <div class="preview-banner" lang="de-CH">
        <span>{{ previewCopy.banner }}</span>
        <span v-if="languages.length > 1" class="flex gap-3">
            <Link
                v-for="locale in languages"
                :key="locale"
                :href="withLanguage(locale)"
                class="link-underline"
                :class="{ 'font-semibold': current() === locale }"
                :aria-current="current() === locale ? 'true' : undefined"
                >{{ languageNames[locale as Locale] }}</Link
            >
        </span>
        <Link :href="dashboard()" class="link-underline font-semibold">{{
            previewCopy.back
        }}</Link>
    </div>
</template>
