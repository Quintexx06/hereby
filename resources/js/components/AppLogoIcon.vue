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

<!-- Hereby mark: the "h." of the wordmark, heavy grotesk, square full stop. -->
<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="10 9 44 46"
        fill="none"
        :class="className"
        v-bind="$attrs"
    >
        <path
            d="M17 12v40M17 33c0-6.4 4.6-10.6 10.6-10.6S38.2 26.6 38.2 33v19"
            stroke="currentColor"
            stroke-width="7.5"
        />
        <rect
            x="43.5"
            y="44.5"
            width="7.5"
            height="7.5"
            fill="currentColor"
            class="text-brand"
        />
    </svg>
</template>
