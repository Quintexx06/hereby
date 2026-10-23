<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import DesktopPreview from '@/components/content/DesktopPreview.vue';
import LanguageTabs from '@/components/content/LanguageTabs.vue';
import { contentPage as copy } from '@/content/content';
import { prefersReducedMotion } from '@/lib/motion';
import { preview } from '@/routes/weddings';
import type { ContentWedding, Locale } from '@/types';

/*
 * The invitation as a guest sees it on a phone, beside the editor on wide
 * screens. It shows what is saved: `version` changes after every save or
 * reorder and loads it again, at the spot the couple had scrolled to.
 * `focus` is the part being edited (usePreviewFocus): the phone scrolls to
 * it. The same page at desktop width opens in a popup below.
 */
const props = defineProps<{
    wedding: ContentWedding;
    version: string;
    focus?: string | null;
}>();
const language = ref<Locale>(props.wedding.languages[0]);
const frame = ref<HTMLIFrameElement | null>(null);
let scrolledTo = 0;

const src = computed(() =>
    preview.url(props.wedding.id, {
        query: { sprache: language.value, einbetten: 1 },
    }),
);

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

/*
 * Scrolls the phone, never the editor: the target's place is measured in
 * the frame. The page may still be rendering, so it looks a few times.
 */
function show(target: string, tries = 8): void {
    const view = frame.value?.contentWindow;
    const page = frame.value?.contentDocument;
    if (!view || !page) {
        return;
    }

    const element =
        target === 'top'
            ? page.body
            : target === 'footer'
              ? page.querySelector('.inv-footer')
              : page.getElementById(target);
    if (!element) {
        if (tries > 0) {
            setTimeout(() => show(target, tries - 1), 200);
        }

        return;
    }

    const top =
        target === 'top'
            ? 0
            : element.getBoundingClientRect().top + view.scrollY - 12;
    view.scrollTo({
        top,
        behavior: prefersReducedMotion() ? 'auto' : 'smooth',
    });
}

watch(
    () => props.focus,
    (target) => target && show(target),
);
</script>

<template>
    <aside class="content-preview" :aria-label="copy.previewLabel">
        <div class="content-preview-phone">
            <div class="content-preview-screen">
                <iframe
                    ref="frame"
                    :key="`${language}-${version}`"
                    :src="src"
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
        <DesktopPreview :src="src" />
        <p class="field-hint max-w-72 text-center">{{ copy.previewHint }}</p>
    </aside>
</template>
