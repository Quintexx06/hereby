<!-- Panorama's first screen, small: lights drifting, the names running past. -->
<template>
    <span class="mini mini-panorama">
        <span class="mini-bokeh" aria-hidden="true" />
        <span class="mini-marquee" aria-hidden="true">
            <span class="mini-track">
                <span v-for="copy in 4" :key="copy" class="mini-run"
                    >Anna &amp; Luca<span class="text-brand"> · </span></span
                >
            </span>
        </span>
        <span class="mini-panorama-meta">
            <span class="mini-date text-[1.6rem]">19. Juni 2027</span>
            <span class="text-[0.65rem] text-muted-foreground"
                >heiraten · Vitznau</span
            >
        </span>
    </span>
</template>
