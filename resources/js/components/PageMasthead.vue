<script setup lang="ts">
import MastheadScene from '@/components/MastheadScene.vue';
import type { MastheadSceneName } from '@/types';

defineProps<{
    title: string;
    lede?: string;
    /** Which 3D scene turns in the margin; none where the page has its own panel. */
    scene?: MastheadSceneName;
}>();
</script>

<!--
    Every app page opens the same way: the title set large over a firm rule,
    a line of context, actions as words, and the page's own scene in the margin.
-->
<template>
    <header class="masthead" :data-scene="scene ?? undefined">
        <div class="masthead-copy">
            <h1 class="masthead-title">
                <slot name="title">{{ title }}</slot>
            </h1>
            <p v-if="lede || $slots.lede" class="masthead-meta text-pretty">
                <slot name="lede">{{ lede }}</slot>
            </p>
            <div v-if="$slots.actions" class="masthead-actions">
                <slot name="actions" />
            </div>
        </div>
        <div v-if="$slots.aside" class="masthead-aside">
            <slot name="aside" />
        </div>
        <MastheadScene v-if="scene" :key="scene" :scene="scene" />
    </header>
</template>
