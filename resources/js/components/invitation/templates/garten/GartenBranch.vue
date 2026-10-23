<script setup lang="ts">
defineProps<{ side: 'left' | 'right' }>();

/* One hand-drawn branch: a stem, leaves along it, two small blossoms. */
const leaves = [
    { x: 54, y: 330, r: -38 },
    { x: 78, y: 268, r: 32 },
    { x: 60, y: 214, r: -44 },
    { x: 92, y: 160, r: 28 },
    { x: 84, y: 104, r: -30 },
    { x: 112, y: 58, r: 36 },
];
const blossoms = [
    { x: 100, y: 190 },
    { x: 118, y: 22 },
];
</script>

<!-- Decorative: drawn with CSS on the first paint, swayed by GSAP on scroll. -->
<template>
    <svg
        class="garten-branch"
        :data-side="side"
        viewBox="0 0 160 400"
        fill="none"
        aria-hidden="true"
    >
        <path
            class="garten-stem"
            pathLength="1"
            d="M30 398 C 70 330, 44 270, 74 214 S 118 120, 96 70 S 120 20, 124 4"
        />
        <g
            v-for="(leaf, index) in leaves"
            :key="index"
            class="garten-leaf"
            :style="{ '--i': index }"
            :transform="`translate(${leaf.x} ${leaf.y}) rotate(${leaf.r})`"
        >
            <path d="M0 0 C 10 -14, 30 -14, 40 0 C 30 14, 10 14, 0 0 Z" />
            <path class="garten-vein" pathLength="1" d="M2 0 L 36 0" />
        </g>
        <g
            v-for="(blossom, index) in blossoms"
            :key="`b${index}`"
            class="garten-blossom"
            :style="{ '--i': index + 6 }"
            :transform="`translate(${blossom.x} ${blossom.y})`"
        >
            <circle
                v-for="petal in 5"
                :key="petal"
                r="6"
                :cx="Math.cos((petal * 2 * Math.PI) / 5) * 7"
                :cy="Math.sin((petal * 2 * Math.PI) / 5) * 7"
            />
            <circle class="garten-blossom-heart" r="3.5" />
        </g>
    </svg>
</template>
