<script setup lang="ts">
import {
    Feather,
    Gem,
    Heart,
    Hourglass,
    Landmark,
    Leaf,
    Minus,
    Mountain,
    PartyPopper,
    Square,
    Sun,
    Trees,
} from '@lucide/vue';
import type { LucideIcon } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { look, lookStyles } from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import type { LookStyle } from '@/types/wedding';

/** Mirrors LookStyle::MAX on the server. */
const MAX = 3;

const icons: Record<LookStyle, LucideIcon> = {
    classic: Landmark,
    modern: Square,
    romantic: Heart,
    natural: Leaf,
    boho: Feather,
    rustic: Trees,
    elegant: Gem,
    mediterranean: Sun,
    vintage: Hourglass,
    minimal: Minus,
    playful: PartyPopper,
    mountain: Mountain,
};

const form = useSetupForm();
const styles = Object.keys(lookStyles) as LookStyle[];

/* Once three are picked, the rest wait until one is let go. */
const isLocked = (style: LookStyle) =>
    form.look_styles.length >= MAX && !form.look_styles.includes(style);
</script>

<template>
    <fieldset class="look-section">
        <legend class="look-legend">
            <span class="app-section-title"
                >{{ look.styles }}
                <span class="text-muted-foreground tabular-nums">{{
                    look.stylesCount(form.look_styles.length, MAX)
                }}</span></span
            >
            <span class="field-hint">{{ look.stylesHint(MAX) }}</span>
        </legend>
        <div class="look-styles">
            <label
                v-for="style in styles"
                :key="style"
                class="style-option"
                :data-locked="isLocked(style)"
            >
                <input
                    v-model="form.look_styles"
                    type="checkbox"
                    :value="style"
                    :disabled="isLocked(style)"
                    class="sr-only"
                />
                <component
                    :is="icons[style]"
                    class="style-icon"
                    aria-hidden="true"
                />
                <span class="flex min-w-0 flex-col gap-0.5">
                    <span class="font-medium">{{
                        lookStyles[style].name
                    }}</span>
                    <span class="text-xs text-muted-foreground">{{
                        lookStyles[style].mood
                    }}</span>
                </span>
            </label>
        </div>
        <InputError :message="form.errors.look_styles" />
    </fieldset>
</template>
