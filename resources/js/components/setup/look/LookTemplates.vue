<script setup lang="ts">
import { look, templates } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import type { WeddingTemplate } from '@/types/wedding';

const form = useSetupForm();
const options = Object.keys(templates) as WeddingTemplate[];
</script>

<!-- Three templates, each shown as a tiny drawing of its first screen. -->
<template>
    <fieldset class="look-section">
        <legend class="look-legend">
            <span class="app-section-title">{{ look.templates }}</span>
            <span class="field-hint">{{ look.templatesHint }}</span>
        </legend>
        <div class="look-templates">
            <label v-for="option in options" :key="option" class="theme-option">
                <input
                    v-model="form.template"
                    type="radio"
                    name="template"
                    :value="option"
                    class="sr-only"
                />
                <span
                    :data-theme="form.theme"
                    :data-template="option"
                    class="template-sample"
                    aria-hidden="true"
                >
                    <span class="template-sample-frame" />
                    <span class="template-sample-names">Anna &amp; Luca</span>
                    <span class="template-sample-line" />
                </span>
                <span class="flex flex-col gap-0.5 px-1 pb-1">
                    <span class="font-medium">{{
                        templates[option].name
                    }}</span>
                    <span class="text-xs text-muted-foreground">{{
                        templates[option].mood
                    }}</span>
                </span>
            </label>
        </div>
    </fieldset>
</template>
