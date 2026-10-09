<script setup lang="ts">
import { computed } from 'vue';
import { look, themes } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import type { ThemeSuggestion } from '@/types/setup';
import type { WeddingTheme } from '@/types/wedding';

const props = defineProps<{
    suggestion: ThemeSuggestion | null;
}>();

const form = useSetupForm();

/* The suggested theme leads the list; the rest keep their order. */
const order = computed(() => {
    const all = Object.keys(themes) as WeddingTheme[];
    const first = props.suggestion?.theme;

    return first ? [first, ...all.filter((theme) => theme !== first)] : all;
});
</script>

<template>
    <fieldset class="grid grid-cols-2 gap-3 sm:grid-cols-3">
        <legend class="sr-only">Look</legend>
        <label v-for="theme in order" :key="theme" class="theme-option">
            <input
                v-model="form.theme"
                type="radio"
                name="theme"
                :value="theme"
                class="sr-only"
            />
            <span :data-theme="theme" class="theme-sample" aria-hidden="true">
                <span class="text-[0.65rem] text-muted-foreground"
                    >Für Heidi und Peter</span
                >
                <span
                    class="font-display text-lg leading-none font-semibold tracking-tight"
                    >Anna &amp; Luca</span
                >
                <span class="mt-1 h-1.5 w-10 rounded-full bg-primary" />
            </span>
            <span class="flex flex-col gap-0.5 px-1 pb-1">
                <span class="font-medium">{{ themes[theme].name }}</span>
                <span class="text-xs text-muted-foreground">{{
                    themes[theme].mood
                }}</span>
                <span
                    v-if="suggestion?.theme === theme"
                    class="mt-1 text-xs font-medium text-brand"
                >
                    {{ look.suggested }}: {{ suggestion.reason }}
                </span>
            </span>
        </label>
    </fieldset>
</template>
