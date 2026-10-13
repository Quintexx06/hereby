<script setup lang="ts">
import { computed, ref } from 'vue';
import ShowcaseWindow from '@/components/marketing/ShowcaseWindow.vue';
import { studio } from '@/content/landing';

type AccentSlug = (typeof studio.accents)[number]['slug'];

const selected = ref<AccentSlug>(studio.accents[0].slug);
const active = computed(
    () =>
        studio.accents.find((accent) => accent.slug === selected.value) ??
        studio.accents[0],
);
</script>

<!--
    A finished couple's site, shown in a browser window. The visitor picks an
    accent and only the accents change: the page itself stays porcelain.
-->
<template>
    <section class="overflow-hidden">
        <div class="page-container section flex flex-col gap-12 sm:gap-16">
            <div
                v-reveal
                class="flex reveal flex-col gap-8 lg:flex-row lg:items-end lg:justify-between"
            >
                <header class="flex max-w-3xl flex-col gap-6">
                    <h2 class="headline">{{ studio.title }}</h2>
                    <p class="lede">{{ studio.lede }}</p>
                </header>

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

            <ShowcaseWindow v-reveal class="reveal" :accent="selected" />
            <p class="caption -mt-6 text-center sm:-mt-10">
                {{ studio.example }}
            </p>
        </div>
    </section>
</template>
