<script setup lang="ts">
import { ARVE_CONTOURS } from '@/lib/topo';
</script>

<!--
    Contour lines of a Swiss map, every fifth one heavier as on the real
    sheet; each draws itself in once, from the summit out. Decorative.
-->
<template>
    <svg
        class="arve-topo"
        viewBox="0 0 1200 600"
        preserveAspectRatio="xMidYMid slice"
        aria-hidden="true"
    >
        <path
            v-for="(line, index) in ARVE_CONTOURS"
            :key="index"
            :d="line"
            pathLength="1"
            :style="{ '--i': index % 11 }"
            :data-major="index % 5 === 0 || undefined"
        />
    </svg>
</template>
