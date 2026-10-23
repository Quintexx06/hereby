<!-- Three lines of water that drift slowly sideways, the lake under the sun. Decorative. -->
<template>
    <svg
        class="lido-waves"
        viewBox="0 0 1200 90"
        preserveAspectRatio="none"
        aria-hidden="true"
    >
        <g class="lido-waves-drift">
            <path
                v-for="line in 3"
                :key="line"
                :d="`M0 ${line * 24} q 50 -14 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0 t 100 0`"
            />
        </g>
    </svg>
</template>
