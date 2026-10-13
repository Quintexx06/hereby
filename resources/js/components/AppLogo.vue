<!--
    The wordmark in the nav; in the icon rail its first letter and full stop.
    On hover the letters drop through and fall back in, one after another,
    as the footer's wordmark arrives.
-->
<template>
    <span class="wordmark wordmark-drop app-nav-wide text-[1.6rem]"
        ><span
            v-for="(letter, index) in 'hereby'"
            :key="index"
            class="wordmark-letter"
            :style="{ '--i': index }"
            >{{ letter }}</span
        ><span class="wordmark-letter wordmark-stop" :style="{ '--i': 6 }"
            >.</span
        ></span
    >
    <span class="wordmark app-nav-railonly text-xl">
        h<span class="wordmark-stop">.</span>
    </span>
</template>
