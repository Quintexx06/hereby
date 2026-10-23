<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowUpRight } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import DesignShots from '@/components/marketing/DesignShots.vue';
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
    The five designs (ADR 0015, 0016): the chosen one large, as its real
    first screens on a computer and a phone, with its mood and the whole
    sample; the five beside it to pick from, our suggestion marked (tagged
    in the wide column, a dot and a line under the row of five where it is
    narrow); and for anything beyond them, the made-to-measure.
-->
<template>
    <div class="look @container flex flex-col gap-8">
        <div class="look-picker">
            <div class="look-stage" aria-live="polite">
                <Transition name="studio-swap" mode="out-in">
                    <DesignShots
                        :key="form.template"
                        :design="form.template"
                        eager
                    />
                </Transition>
                <div class="flex flex-col gap-2">
                    <p class="look-stage-name">
                        {{ templates[form.template].name
                        }}<span class="text-brand">.</span>
                    </p>
                    <p class="max-w-xl text-muted-foreground">
                        {{ templates[form.template].mood }}
                    </p>
                    <a
                        :href="sample.url(form.template)"
                        target="_blank"
                        rel="noopener"
                        class="link-underline mt-1 inline-flex items-center gap-1 self-start font-medium"
                        >{{ look.live }} <ArrowUpRight class="size-4"
                    /></a>
                </div>
            </div>

            <fieldset class="look-options">
                <legend class="sr-only">{{ look.chosen }}</legend>
                <label
                    v-for="option in options"
                    :key="option"
                    class="design-option"
                    :data-suggested="suggestion?.template === option"
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
                    <span class="design-option-name">
                        {{ templates[option].name }}
                        <span
                            v-if="suggestion?.template === option"
                            class="design-option-tag"
                            :title="suggestion.reason"
                            >{{ look.suggested }}</span
                        >
                    </span>
                </label>
                <p v-if="suggestion" class="look-suggestion">
                    {{ look.suggested }}:
                    {{ templates[suggestion.template].name }}.
                    {{ suggestion.reason }}
                </p>
            </fieldset>
        </div>
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
