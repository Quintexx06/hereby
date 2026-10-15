<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { X } from '@lucide/vue';
import {
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogOverlay,
    DialogPortal,
    DialogRoot,
    DialogTitle,
} from 'reka-ui';
import ShowcaseWindow from '@/components/marketing/ShowcaseWindow.vue';
import type { ShowcaseCoupleSite } from '@/composables/useShowcaseCouple';
import { studio } from '@/content/landing';
import { register } from '@/routes';

defineProps<{
    template: { slug: string; name: string; mood: string } | null;
    accent: string;
    couple: ShowcaseCoupleSite;
    /** Where the visitor clicked: the window grows from there. */
    origin: string;
}>();

const open = defineModel<boolean>('open', { required: true });

/* Focus the site itself, so arrow keys and space scroll it straight away. */
function focusSite(event: Event): void {
    event.preventDefault();
    (event.currentTarget as HTMLElement | null)
        ?.querySelector<HTMLElement>('.showcase-site')
        ?.focus({ preventScroll: true });
}
</script>

<!-- The whole demo site in a large window that grows from the click. -->
<template>
    <DialogRoot v-model:open="open">
        <DialogPortal>
            <DialogOverlay class="showcase-overlay" />
            <DialogContent
                v-if="template"
                class="showcase-dialog"
                :style="{ transformOrigin: origin }"
                @open-auto-focus="focusSite"
            >
                <DialogTitle class="sr-only">{{ template.name }}</DialogTitle>
                <DialogDescription class="sr-only">{{
                    template.mood
                }}</DialogDescription>
                <ShowcaseWindow
                    :template="template.slug"
                    :active="open"
                    :accent="accent"
                    :couple="couple"
                    class="showcase-window-full"
                >
                    <template #bar>
                        <Link :href="register()" class="showcase-start">
                            {{ studio.start }}
                        </Link>
                        <DialogClose
                            class="showcase-close"
                            :aria-label="studio.close"
                        >
                            <X class="size-4" />
                        </DialogClose>
                    </template>
                </ShowcaseWindow>
            </DialogContent>
        </DialogPortal>
    </DialogRoot>
</template>
