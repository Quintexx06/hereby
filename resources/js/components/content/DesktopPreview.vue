<script setup lang="ts">
import { Monitor } from '@lucide/vue';
import { onBeforeUnmount, ref, watch } from 'vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { contentPage as copy } from '@/content/content';

defineProps<{ src: string }>();

/* A real desktop width, scaled down to the popup as the phone is to its frame. */
const WIDTH = 1440;
const screen = ref<HTMLElement | null>(null);
const scale = ref(1);
const height = ref(900);
let observer: ResizeObserver | undefined;

watch(screen, (element) => {
    observer?.disconnect();
    if (!element) {
        return;
    }

    observer = new ResizeObserver(([entry]) => {
        scale.value = Math.min(1, entry.contentRect.width / WIDTH);
        height.value = entry.contentRect.height / scale.value;
    });
    observer.observe(element);
});

onBeforeUnmount(() => observer?.disconnect());

/*
 * Opening focuses «Schliessen», not the page in the frame: a focused frame
 * would swallow Escape, and the popup would not close from the keyboard.
 */
function focusClose(event: Event): void {
    event.preventDefault();
    (event.target as HTMLElement | null)
        ?.querySelector<HTMLElement>('[data-slot="dialog-close"]')
        ?.focus();
}
</script>

<!--
    «Am Computer ansehen»: the invitation at desktop width in a popup, in a
    browser window, so the couple sees both sizes their guests will see.
-->
<template>
    <Dialog>
        <DialogTrigger as-child>
            <button type="button" class="text-action hit-area">
                <Monitor class="size-4" /> {{ copy.desktop }}
            </button>
        </DialogTrigger>
        <DialogContent class="desktop-preview" @open-auto-focus="focusClose">
            <div class="desktop-preview-bar">
                <span class="studio-dots" aria-hidden="true" />
                <DialogTitle class="desktop-preview-title">
                    {{ copy.desktopTitle }}
                </DialogTitle>
                <DialogDescription class="sr-only">
                    {{ copy.desktopHint }}
                </DialogDescription>
            </div>
            <div ref="screen" class="desktop-preview-screen">
                <iframe
                    :src="src"
                    :title="copy.desktopTitle"
                    class="desktop-preview-frame"
                    :style="{
                        width: `${WIDTH}px`,
                        height: `${height}px`,
                        transform: `scale(${scale})`,
                    }"
                />
            </div>
        </DialogContent>
    </Dialog>
</template>
