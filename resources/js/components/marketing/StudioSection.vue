<script setup lang="ts">
import { computed, ref } from 'vue';
import ShowcaseCoupleForm from '@/components/marketing/ShowcaseCoupleForm.vue';
import ShowcaseCarousel from '@/components/marketing/ShowcaseCarousel.vue';
import { useShowcaseCouple } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';

type AccentSlug = (typeof studio.accents)[number]['slug'];

const selected = ref<AccentSlug>(studio.accents[0].slug);
const active = computed(
    () =>
        studio.accents.find((accent) => accent.slug === selected.value) ??
        studio.accents[0],
);

const { couple, site } = useShowcaseCouple(studio.site.couple);
</script>

<!--
    The three templates as finished sites in browser windows, side by side.
    Visitors type their own names and date into them and pick an accent:
    only the accents change, the page itself stays porcelain.
-->
<template>
    <section id="studio" class="overflow-hidden">
        <div class="page-container section flex flex-col gap-10 sm:gap-12">
            <header v-reveal class="flex max-w-3xl reveal flex-col gap-6">
                <h2 class="headline">{{ studio.title }}</h2>
                <p class="lede">{{ studio.lede }}</p>
            </header>

            <div
                class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between"
            >
                <ShowcaseCoupleForm
                    v-model:first="couple.first"
                    v-model:second="couple.second"
                    v-model:date="couple.date"
                    :placeholder="studio.site.couple"
                />

                <div class="flex flex-col gap-3 lg:shrink-0 lg:items-end">
                    <div
                        role="radiogroup"
                        :aria-label="studio.pickerLabel"
                        class="flex flex-wrap gap-2 lg:flex-nowrap"
                    >
                        <button
                            v-for="accent in studio.accents"
                            :key="accent.slug"
                            type="button"
                            role="radio"
                            class="accent-chip"
                            :data-accent="accent.slug"
                            :aria-checked="accent.slug === selected"
                            @click="selected = accent.slug"
                        >
                            <span class="accent-dot" aria-hidden="true" />
                            {{ accent.name }}
                        </button>
                    </div>
                    <p class="caption" aria-live="polite">
                        {{ active.mood }}
                    </p>
                </div>
            </div>

            <ShowcaseCarousel :accent="selected" :couple="site" />
            <p class="caption -mt-4 text-center">{{ studio.example }}</p>
        </div>
    </section>
</template>
