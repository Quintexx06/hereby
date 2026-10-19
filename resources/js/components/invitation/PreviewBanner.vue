<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { previewCopy } from '@/content/content';
import { languages as languageNames } from '@/content/setup';
import { dashboard } from '@/routes';
import type { Locale } from '@/types';

/* Couple-only: the preview is in the guests' language, this bar stays German. */
defineProps<{
    languages: string[];
    /** A venue's sales demo: who it is for, and the three templates to try. */
    demo?: { venue: string; template: string; more: string };
}>();
const page = usePage();
const current = () => page.props.locale;
/* page.url, not window: this also renders on the server. */
const withLanguage = (locale: string) =>
    `${page.url.split('?')[0]}?sprache=${locale}`;
/* The demo link is signed; only «vorlage» may change, the rest stays. */
const withTemplate = (template: string) => {
    const [path, query = ''] = page.url.split('?');
    const params = new URLSearchParams(query);
    params.set('vorlage', template);

    return `${path}?${params.toString()}`;
};
</script>

<template>
    <div v-if="demo" class="preview-banner" lang="de-CH">
        <span>{{ previewCopy.demo(demo.venue) }}</span>
        <span class="flex gap-3">
            <a
                v-for="(label, key) in previewCopy.templates"
                :key="key"
                :href="withTemplate(key)"
                class="link-underline"
                :class="{ 'font-semibold': demo.template === key }"
                :aria-current="demo.template === key ? 'true' : undefined"
                >{{ label }}</a
            >
        </span>
        <a :href="demo.more" class="link-underline font-semibold">{{
            previewCopy.demoMore
        }}</a>
    </div>
    <div v-else class="preview-banner" lang="de-CH">
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
