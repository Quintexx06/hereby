<script setup lang="ts">
import ResponsivePhoto from '@/components/marketing/ResponsivePhoto.vue';
import { studio } from '@/content/landing';

const [wreathPhoto, printPhoto, vinePhoto] = studio.site.photos.gallery;

/**
 * Leaves along the wreath's oval stem (centre 100/125, radii 92/117),
 * leaving the bottom open for the bow. Each leaf follows the stem and
 * alternates between the outer and the inner side.
 */
const wreathLeaves = Array.from({ length: 18 }, (_, index) => {
    const turn = ((205 + index * 17) % 360) * (Math.PI / 180);
    const x = 100 + 92 * Math.sin(turn);
    const y = 125 - 117 * Math.cos(turn);
    const along = Math.atan2(117 * Math.sin(turn), 92 * Math.cos(turn));
    const side = index % 2 === 0 ? -35 : 35;

    return `translate(${x.toFixed(1)} ${y.toFixed(1)}) rotate(${((along * 180) / Math.PI + side).toFixed(1)})`;
});

/** Leaves on the climbing rose: [x, y, angle]. */
const vineLeaves = [
    [11, 222, -150],
    [9, 172, -130],
    [12, 128, -165],
    [24, 56, -125],
    [56, 24, -105],
    [144, 24, -75],
    [190, 132, 20],
];

const leaf = 'M0 0c4-6 12-7 17-3-4 5-11 7-17 3Z';
const rose = 'M0 0c3-3 7 0 4 4-4 4-10-1-7-7 4-6 14-2 12 7-2 7-12 10-17 4';
</script>

<!--
    Garten's photos: one print dropped on the table, and two framed by hand
    in the couple's accent: an oval under a leaf wreath with a bow, and an
    arch with a climbing rose. The lines draw themselves, then the leaves
    open along them.
-->
<template>
    <div class="sc-g-gallery">
        <figure v-reveal class="sc-g-frame sc-g-frame-wreath">
            <div class="sc-g-frame-photo rounded-[50%]">
                <ResponsivePhoto
                    :photo="wreathPhoto"
                    sizes="14rem"
                    class="sc-cover"
                />
            </div>
            <svg class="sc-g-drawing" viewBox="0 0 200 250" aria-hidden="true">
                <path
                    class="sc-g-line"
                    pathLength="1"
                    d="M68.5 235A92 117 0 1 1 131.5 235"
                />
                <path
                    class="sc-g-line"
                    pathLength="1"
                    d="M100 236c-10-10-24-5-15 4 4 4 10 0 15-4 10-10 24-5 15 4-5 4-11 0-15-4l-9 16m9-16 9 16"
                />
                <g
                    v-for="(place, index) in wreathLeaves"
                    :key="index"
                    :transform="place"
                >
                    <path
                        class="sc-g-bud"
                        :style="{ '--i': index }"
                        :d="leaf"
                    />
                </g>
            </svg>
        </figure>

        <span v-reveal="120" class="sc-g-print" :style="{ '--tilt': '-3deg' }">
            <ResponsivePhoto
                :photo="printPhoto"
                sizes="12rem"
                class="aspect-[4/5] w-full object-cover"
            />
        </span>

        <figure v-reveal="240" class="sc-g-frame sc-g-frame-vine">
            <div class="sc-g-frame-photo rounded-t-full">
                <ResponsivePhoto
                    :photo="vinePhoto"
                    sizes="14rem"
                    class="sc-cover"
                />
            </div>
            <svg class="sc-g-drawing" viewBox="0 0 200 260" aria-hidden="true">
                <path
                    class="sc-g-line"
                    pathLength="1"
                    d="M12 256C4 226 18 200 10 172s-6-52 2-72a88 88 0 0 1 176 0c2 14-2 26 4 34 6 8 16 2 12-6-3-6-10-3-8 2"
                />
                <g
                    v-for="([x, y, angle], index) in vineLeaves"
                    :key="index"
                    :transform="`translate(${x} ${y}) rotate(${angle})`"
                >
                    <path
                        class="sc-g-bud"
                        :style="{ '--i': index * 2 }"
                        :d="leaf"
                    />
                </g>
                <path
                    v-for="([x, y], index) in [
                        [100, 12],
                        [176, 58],
                    ]"
                    :key="`rose-${index}`"
                    class="sc-g-bud sc-g-rose"
                    :style="{ '--i': 8 + index * 4 }"
                    :transform="`translate(${x} ${y})`"
                    :d="rose"
                />
            </svg>
        </figure>
    </div>
</template>
