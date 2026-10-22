<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowUpRight } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { look, templates } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import { show as bespoke } from '@/routes/weddings/bespoke';
import { show as sample } from '@/routes/samples';
import type { TemplateSuggestion } from '@/types/setup';
import type { WeddingTemplate } from '@/types/wedding';

defineProps<{
    weddingId: number;
    suggestion: TemplateSuggestion | null;
}>();

const form = useSetupForm();
const options = Object.keys(templates) as WeddingTemplate[];
</script>

<!--
    The five designs as their real first screens (ADR 0015): pick one, look
    at the whole sample, and for anything beyond them, the made-to-measure.
-->
<template>
    <div class="flex flex-col gap-8">
        <fieldset class="look-designs">
            <legend class="sr-only">{{ look.chosen }}</legend>
            <label
                v-for="option in options"
                :key="option"
                class="design-option"
            >
                <input
                    v-model="form.template"
                    type="radio"
                    name="template"
                    :value="option"
                    class="sr-only"
                />
                <span class="design-option-shot">
                    <img
                        :src="`/images/designs/${option}.webp`"
                        alt=""
                        width="960"
                        height="660"
                        loading="lazy"
                        decoding="async"
                    />
                </span>
                <span class="design-option-text">
                    <span class="font-semibold">
                        {{ templates[option].name }}
                        <span
                            v-if="suggestion?.template === option"
                            class="design-option-tag"
                            :title="suggestion.reason"
                            >{{ look.suggested }}</span
                        >
                    </span>
                    <span class="text-sm text-muted-foreground">{{
                        templates[option].mood
                    }}</span>
                    <a
                        :href="sample.url(option)"
                        target="_blank"
                        rel="noopener"
                        class="link-underline mt-1 inline-flex items-center gap-1 self-start text-sm font-medium"
                        >{{ look.live }} <ArrowUpRight class="size-3.5"
                    /></a>
                </span>
            </label>
        </fieldset>
        <InputError :message="form.errors.template" />

        <aside class="look-bespoke">
            <div class="flex flex-col gap-1">
                <p class="font-semibold">{{ look.bespoke }}</p>
                <p class="text-sm text-muted-foreground">
                    {{ look.bespokeBody }}
                </p>
            </div>
            <Link
                :href="bespoke(weddingId)"
                class="link-underline shrink-0 text-sm font-semibold"
                >{{ look.bespokeLink }}</Link
            >
        </aside>
    </div>
</template>
