<script setup lang="ts">
import { X } from '@lucide/vue';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import type { TourStep as Step } from '@/config/tours';
import { landingPhotos } from '@/content/landing-photos';
import { tourCopy as copy } from '@/content/tour';

defineProps<{ step: Step; index: number; total: number }>();
defineEmits<{ back: []; next: []; close: [] }>();

const root = ref<HTMLElement | null>(null);
defineExpose({ root });
</script>

<!-- The card beside the spotlight: what this is, and how to go on. -->
<template>
    <div
        ref="root"
        class="tour-card"
        role="dialog"
        aria-labelledby="tour-title"
        aria-describedby="tour-text"
        tabindex="-1"
    >
        <img
            v-if="step.photo"
            :src="landingPhotos[step.photo].src"
            :alt="landingPhotos[step.photo].alt"
            class="tour-photo"
        />
        <button
            type="button"
            class="tour-close"
            :aria-label="copy.close"
            @click="$emit('close')"
        >
            <X class="size-4" aria-hidden="true" />
        </button>
        <div class="flex flex-col gap-2 p-5 pb-0">
            <p class="caption tabular-nums">
                {{ copy.progress(index + 1, total) }}
            </p>
            <h2 id="tour-title" class="app-section-title text-xl">
                {{ step.title }}
            </h2>
            <p id="tour-text" class="text-sm text-pretty text-muted-foreground">
                {{ step.text }}
            </p>
            <p v-if="step.interactive" class="text-sm font-semibold">
                {{ copy.tryIt }}
            </p>
        </div>
        <div class="flex justify-end gap-2 p-5">
            <Button
                variant="ghost"
                class="rounded-full"
                @click="index > 0 ? $emit('back') : $emit('close')"
            >
                {{ index > 0 ? copy.back : copy.skip }}
            </Button>
            <Button class="rounded-full" @click="$emit('next')">
                {{ index === total - 1 ? copy.done : copy.next }}
            </Button>
        </div>
    </div>
</template>
