<script setup lang="ts">
import { computed } from 'vue';
import { finish } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import { accentVars } from '@/lib/accent';
import type { SiteSection } from '@/types';

const form = useSetupForm();

/* The colour as guests will see it: the picked hue on the theme ladder. */
const sample = computed(() => accentVars(form.accent_color));
const sections = Object.keys(finish.sections) as SiteSection[];

function toggle(section: SiteSection, shown: boolean): void {
    form.hidden_sections = shown
        ? form.hidden_sections.filter((item) => item !== section)
        : [...form.hidden_sections, section];
}
</script>

<!-- «Feinschliff»: an own accent colour, and which built-in parts to show. -->
<template>
    <fieldset class="look-section">
        <legend class="look-legend">
            <span class="app-section-title">{{ finish.title }}</span>
            <span class="field-hint">{{ finish.hint }}</span>
        </legend>

        <div class="look-accent">
            <label class="look-accent-pick">
                <input
                    type="color"
                    :value="form.accent_color ?? '#9b3b5a'"
                    class="look-accent-input"
                    :aria-label="finish.colour"
                    @input="
                        form.accent_color = (
                            $event.target as HTMLInputElement
                        ).value
                    "
                />
                <span class="flex flex-col gap-0.5">
                    <span class="font-medium">{{ finish.colour }}</span>
                    <span class="text-sm text-muted-foreground">{{
                        form.accent_color ? finish.colourOn : finish.colourOff
                    }}</span>
                </span>
            </label>
            <span
                v-if="sample"
                data-theme="ivory"
                data-custom-accent
                :style="sample"
                class="look-accent-sample"
                aria-hidden="true"
            >
                <span class="font-display text-lg font-semibold text-brand"
                    >Anna &amp; Luca</span
                >
                <span class="h-1.5 w-10 rounded-full bg-primary" />
            </span>
            <button
                v-if="form.accent_color"
                type="button"
                class="text-action"
                @click="form.accent_color = null"
            >
                {{ finish.colourReset }}
            </button>
        </div>

        <div class="flex flex-col gap-2">
            <p class="field-label">{{ finish.show }}</p>
            <label
                v-for="section in sections"
                :key="section"
                class="look-section-toggle"
            >
                <input
                    type="checkbox"
                    :checked="!form.hidden_sections.includes(section)"
                    class="size-4 accent-foreground"
                    @change="
                        toggle(
                            section,
                            ($event.target as HTMLInputElement).checked,
                        )
                    "
                />
                <span>{{ finish.sections[section] }}</span>
            </label>
        </div>
    </fieldset>
</template>
