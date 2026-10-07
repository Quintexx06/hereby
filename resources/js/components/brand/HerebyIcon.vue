<script setup lang="ts">
export type HerebyIconName =
    | 'link'
    | 'events'
    | 'clock'
    | 'key'
    | 'template'
    | 'language'
    | 'calendar'
    | 'megaphone'
    | 'forever'
    | 'glass'
    | 'dinner'
    | 'music';

defineProps<{
    name: HerebyIconName;
}>();
</script>

<!--
    Hereby's own icon set: 32px grid, 1.5px stroke, round caps, one blush
    detail per icon (the "full stop" of the wordmark). Drawn, not stock.
    link   — a private link: two interlocked rings with a key bit, no lock.
    events — an invitation card with three acts, one of them ticked.
    clock  — a stopwatch whose hand stops before the minute.
-->
<template>
    <svg
        viewBox="0 0 32 32"
        fill="none"
        stroke="currentColor"
        stroke-width="1.5"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="hereby-icon"
        aria-hidden="true"
    >
        <template v-if="name === 'link'">
            <path pathLength="1" class="draw" d="M13.5 18.5 18.5 13.5" />
            <path
                pathLength="1"
                class="draw"
                d="M15 10.5l2.2-2.2a4.6 4.6 0 0 1 6.5 6.5L21.5 17"
            />
            <path
                pathLength="1"
                class="draw"
                d="M17 21.5l-2.2 2.2a4.6 4.6 0 0 1-6.5-6.5L10.5 15"
            />
            <circle cx="24.5" cy="24.5" r="1.6" class="hereby-icon-dot" />
        </template>

        <template v-else-if="name === 'events'">
            <rect
                pathLength="1"
                class="draw"
                x="7"
                y="5"
                width="18"
                height="22"
                rx="1.5"
            />
            <path pathLength="1" class="draw" d="M11 11h7M11 16h10M11 21h5" />
            <path
                pathLength="1"
                class="draw hereby-icon-accent"
                d="m19.5 20.2 1.6 1.6 3.4-3.6"
            />
        </template>

        <template v-else-if="name === 'key'">
            <circle pathLength="1" class="draw" cx="11" cy="16" r="5" />
            <path pathLength="1" class="draw" d="M16 16h11M23 16v4M27 16v3" />
            <circle cx="11" cy="16" r="1.4" class="hereby-icon-dot" />
        </template>

        <template v-else-if="name === 'template'">
            <rect
                pathLength="1"
                class="draw"
                x="6"
                y="6"
                width="20"
                height="20"
                rx="1.5"
            />
            <path pathLength="1" class="draw" d="M6 13h20M13 13v13" />
            <path
                pathLength="1"
                class="draw hereby-icon-accent"
                d="M17 18h5M17 21.5h3"
            />
        </template>

        <template v-else-if="name === 'language'">
            <path pathLength="1" class="draw" d="M6 7h16v11h-9l-5 4v-4H6z" />
            <path pathLength="1" class="draw" d="M10 11.5h8M10 14.5h5" />
            <path
                pathLength="1"
                class="draw hereby-icon-accent"
                d="M22 12h4v10h-2v3l-4-3h-6"
            />
        </template>

        <template v-else-if="name === 'calendar'">
            <rect
                pathLength="1"
                class="draw"
                x="6"
                y="8"
                width="20"
                height="18"
                rx="1.5"
            />
            <path pathLength="1" class="draw" d="M6 13h20M11 5v5M21 5v5" />
            <circle cx="11" cy="18" r="1.3" class="hereby-icon-dot" />
            <circle cx="16" cy="18" r="1.3" class="hereby-icon-dot" />
            <circle cx="21" cy="21.5" r="1.3" class="hereby-icon-dot" />
        </template>

        <template v-else-if="name === 'megaphone'">
            <path pathLength="1" class="draw" d="M7 13v6h4l9 5V8l-9 5z" />
            <path pathLength="1" class="draw" d="M11 19l1.5 6h3L14 20.5" />
            <path
                pathLength="1"
                class="draw hereby-icon-accent"
                d="M24 12.5a4.5 4.5 0 0 1 0 7"
            />
        </template>

        <template v-else-if="name === 'forever'">
            <path
                pathLength="1"
                class="draw"
                d="M5 16c0-2.6 2-4.3 4.3-4.3 4 0 9.4 8.6 13.4 8.6 2.3 0 4.3-1.7 4.3-4.3s-2-4.3-4.3-4.3c-4 0-9.4 8.6-13.4 8.6C7 20.3 5 18.6 5 16z"
            />
            <circle cx="22.7" cy="16" r="1.4" class="hereby-icon-dot" />
        </template>

        <template v-else-if="name === 'glass'">
            <path
                pathLength="1"
                class="draw"
                d="M11 5h10l-1 8a4 4 0 0 1-8 0z"
            />
            <path pathLength="1" class="draw" d="M16 17v9M12 26h8" />
            <path
                pathLength="1"
                class="draw hereby-icon-accent"
                d="M12.3 10h7.4"
            />
        </template>

        <template v-else-if="name === 'dinner'">
            <circle pathLength="1" class="draw" cx="16" cy="16" r="7" />
            <path
                pathLength="1"
                class="draw"
                d="M5 6v6a2 2 0 0 0 2 2v12M7 6v5M27 6c-2 1-3 4-3 8h3v12"
            />
            <circle cx="16" cy="16" r="1.4" class="hereby-icon-dot" />
        </template>

        <template v-else-if="name === 'music'">
            <path pathLength="1" class="draw" d="M12 23V8l13-3v15" />
            <circle pathLength="1" class="draw" cx="9.5" cy="23" r="2.5" />
            <circle
                pathLength="1"
                class="draw hereby-icon-accent"
                cx="22.5"
                cy="20"
                r="2.5"
            />
        </template>

        <template v-else>
            <circle pathLength="1" class="draw" cx="16" cy="18" r="9" />
            <path
                pathLength="1"
                class="draw"
                d="M13.5 5.5h5M16 5.5V9M23 10.5l1.6-1.6"
            />
            <path pathLength="1" class="draw" d="M16 18V13" />
            <path
                pathLength="1"
                class="draw hereby-icon-accent"
                d="M16 18l4.2 2.4"
            />
            <circle cx="16" cy="18" r="1.1" class="hereby-icon-dot" />
        </template>
    </svg>
</template>
