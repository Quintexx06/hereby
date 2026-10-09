<script setup lang="ts">
import RosesCanvas from '@/components/marketing/RosesCanvas.vue';

defineProps<{
    title: string;
    lede?: string;
    /** Turn the roses off where the page already has a night panel. */
    plain?: boolean;
}>();
</script>

<!--
    Every app page opens the same way: the title set large over a firm rule,
    a line of context, actions as words, and three roses turning in the margin.
-->
<template>
    <header class="masthead" :data-roses="!plain">
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
        <RosesCanvas v-if="!plain" />
    </header>
</template>
