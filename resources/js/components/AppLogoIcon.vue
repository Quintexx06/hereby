<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<!-- Hereby mark: a seal ring around a single signature stroke. -->
<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 40 40"
        fill="none"
        stroke="currentColor"
        stroke-width="2.5"
        stroke-linecap="round"
        stroke-linejoin="round"
        :class="className"
        v-bind="$attrs"
    >
        <circle cx="20" cy="20" r="17" />
        <path
            d="M10.5 25c3-9.5 6.5-13 8-10.5S15 27.5 17.5 26.5s4-9 6-8.5-1 7 1.5 7 3.5-3 5-4.5"
        />
    </svg>
</template>
