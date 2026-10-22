<script setup lang="ts">
import { ArrowUpRight } from '@lucide/vue';
import { computed, ref } from 'vue';
import { studio } from '@/content/landing';
import { templates } from '@/content/setup';
import { show as sample } from '@/routes/samples';
import type { WeddingTemplate } from '@/types';

const designs = Object.keys(templates) as WeddingTemplate[];
const selected = ref<WeddingTemplate>(designs[0]);
const active = computed(() => templates[selected.value]);
const tabs = ref<HTMLButtonElement[]>([]);

/* Arrow keys move along the tabs, as in any tab list. */
function step(by: number): void {
    const index =
        (designs.indexOf(selected.value) + by + designs.length) %
        designs.length;
    selected.value = designs[index];
    tabs.value[index]?.focus();
}
</script>

<!--
    The five designs (ADR 0015) as their real first screens: the wide one
    in a browser window, the phone beside it, and the way to the whole
    sample site. Nothing moves until the visitor picks a design.
-->
<template>
    <section id="studio" class="overflow-hidden">
        <div class="page-container section flex flex-col gap-10 sm:gap-12">
            <header v-reveal class="flex max-w-3xl reveal flex-col gap-6">
                <h2 class="headline">{{ studio.title }}</h2>
                <p class="lede">{{ studio.lede }}</p>
            </header>

            <div
                role="tablist"
                :aria-label="studio.designsLabel"
                class="studio-tabs"
                @keydown.right.prevent="step(1)"
                @keydown.left.prevent="step(-1)"
            >
                <button
                    v-for="design in designs"
                    :id="`studio-tab-${design}`"
                    :key="design"
                    ref="tabs"
                    type="button"
                    role="tab"
                    class="studio-tab"
                    :aria-selected="design === selected"
                    :aria-controls="`studio-panel`"
                    :tabindex="design === selected ? 0 : -1"
                    @click="selected = design"
                >
                    {{ templates[design].name }}
                </button>
            </div>

            <div
                id="studio-panel"
                role="tabpanel"
                class="studio-stage"
                :aria-labelledby="`studio-tab-${selected}`"
            >
                <Transition name="studio-swap" mode="out-in">
                    <div :key="selected" class="studio-shots">
                        <figure class="studio-window">
                            <div class="studio-bar" aria-hidden="true">
                                <span class="studio-dots" />
                                <span class="studio-url">{{ studio.url }}</span>
                            </div>
                            <img
                                :src="`/images/designs/${selected}.webp`"
                                :alt="studio.shot(active.name)"
                                width="960"
                                height="660"
                                loading="lazy"
                                decoding="async"
                            />
                        </figure>
                        <figure class="studio-phone" aria-hidden="true">
                            <img
                                :src="`/images/designs/${selected}-phone.webp`"
                                alt=""
                                width="390"
                                height="844"
                                loading="lazy"
                                decoding="async"
                            />
                        </figure>
                    </div>
                </Transition>
                <div class="studio-caption" aria-live="polite">
                    <p class="studio-name">
                        {{ active.name }}<span class="text-brand">.</span>
                    </p>
                    <p class="max-w-xl text-muted-foreground">
                        {{ active.mood }}
                    </p>
                    <a
                        :href="sample.url(selected)"
                        class="studio-live"
                        target="_blank"
                        rel="noopener"
                        >{{ studio.live }} <ArrowUpRight class="size-4"
                    /></a>
                </div>
            </div>
            <p class="caption -mt-4">{{ studio.example }}</p>
        </div>
    </section>
</template>
