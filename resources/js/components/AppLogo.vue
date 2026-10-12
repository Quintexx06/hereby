<!--
    The wordmark in the sidebar; collapsed to its first letter and full stop.
    On hover the letters drop through and fall back in, one after another,
    as the footer's wordmark arrives.
-->
<template>
    <span
        class="wordmark wordmark-drop text-[1.6rem] group-data-[collapsible=icon]:hidden"
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
    <span class="wordmark hidden text-xl group-data-[collapsible=icon]:inline">
        h<span class="wordmark-stop">.</span>
    </span>
</template>
