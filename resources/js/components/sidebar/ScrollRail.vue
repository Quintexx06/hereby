<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';

/*
 * The app page's scrollbar, drawn by us: a slim pill in the night frame's
 * right margin. It takes no layout space, so the workspace never shifts
 * when a page grows long enough to scroll, and it only shows while the
 * page moves or the pointer is near. Drag it to scroll.
 */
const thumb = ref({ top: 0, height: 0 });
const scrollable = ref(false);
const awake = ref(false);
let sleep: ReturnType<typeof setTimeout> | undefined;
let observer: ResizeObserver | undefined;
let drag: { y: number; scroll: number } | null = null;

function measure(): void {
    const root = document.documentElement;
    const view = window.innerHeight;
    const full = root.scrollHeight;
    scrollable.value = full > view + 1;
    const track = view - 16;
    const height = Math.max((view / full) * track, 40);
    thumb.value = {
        height,
        top: 8 + (window.scrollY / Math.max(full - view, 1)) * (track - height),
    };
}

function wake(): void {
    measure();
    awake.value = true;
    clearTimeout(sleep);
    sleep = setTimeout(() => (awake.value = drag !== null), 900);
}

function grab(event: PointerEvent): void {
    drag = { y: event.clientY, scroll: window.scrollY };
    (event.currentTarget as HTMLElement).setPointerCapture(event.pointerId);
}

function move(event: PointerEvent): void {
    if (!drag) {
        return;
    }

    const root = document.documentElement;
    const ratio =
        (root.scrollHeight - window.innerHeight) /
        (window.innerHeight - 16 - thumb.value.height);
    window.scrollTo({ top: drag.scroll + (event.clientY - drag.y) * ratio });
}

function release(): void {
    drag = null;
    wake();
}

onMounted(() => {
    measure();
    window.addEventListener('scroll', wake, { passive: true });
    window.addEventListener('resize', measure, { passive: true });
    observer = new ResizeObserver(measure);
    observer.observe(document.body);
});

onBeforeUnmount(() => {
    clearTimeout(sleep);
    observer?.disconnect();
    window.removeEventListener('scroll', wake);
    window.removeEventListener('resize', measure);
});
</script>

<!-- Decorative for AT: keyboard, wheel and touch scroll the page as ever. -->
<template>
    <div
        v-show="scrollable"
        class="scroll-rail"
        :data-awake="awake"
        aria-hidden="true"
        @pointerenter="wake"
    >
        <span
            class="scroll-rail-thumb"
            :style="{
                translate: `0 ${thumb.top}px`,
                height: `${thumb.height}px`,
            }"
            @pointerdown="grab"
            @pointermove="move"
            @pointerup="release"
            @pointercancel="release"
        />
    </div>
</template>
