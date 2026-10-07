<script setup lang="ts">
import { toRef } from 'vue';
import { useCountUp } from '@/composables/motion/useCountUp';

const props = defineProps<{
    figure: string;
    unit: string;
    title: string;
    body: string;
    /** The travelling light on the timeline has reached this step. */
    active: boolean;
}>();

const count = useCountUp(toRef(props, 'active'), Number(props.figure));
</script>

<template>
    <li class="step" :data-active="active">
        <span class="step-dot" aria-hidden="true" />
        <p class="step-figure" aria-hidden="true">
            {{ count }}<span class="step-unit">{{ unit }}</span>
        </p>
        <h3 class="title">
            <span class="sr-only">{{ figure }} {{ unit }}: </span>{{ title }}
        </h3>
        <p class="body-copy">{{ body }}</p>
    </li>
</template>
