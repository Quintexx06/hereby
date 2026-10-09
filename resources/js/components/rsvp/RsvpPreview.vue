<script setup lang="ts">
import { Plus } from '@lucide/vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import { rsvpPreview as copy } from '@/content/rsvp';
import type { WeddingTheme } from '@/types';

defineProps<{
    theme: WeddingTheme;
    menus: string[];
    shuttle: boolean;
    stay: boolean;
    song: boolean;
}>();
</script>

<!-- The guest's reply at phone scale, re-drawn as the couple ticks questions. -->
<template>
    <div class="phone-frame" aria-hidden="true">
        <WeddingThemeScope
            :theme="theme"
            lang="de-CH"
            :fill="false"
            class="overflow-hidden rounded-[2.1rem]"
        >
            <div class="phone-screen gap-4">
                <p class="font-display text-2xl font-semibold">
                    {{ copy.title }}
                </p>
                <div class="flex flex-col gap-2 border-t border-rule pt-3">
                    <p class="text-sm font-medium">{{ copy.person }}</p>
                    <div class="flex gap-1.5">
                        <span class="preview-chip is-on">{{
                            copy.attending
                        }}</span>
                        <span class="preview-chip">{{ copy.declined }}</span>
                    </div>
                    <TransitionGroup
                        v-if="menus.length"
                        tag="div"
                        name="preview"
                        class="flex flex-wrap gap-1.5"
                    >
                        <span
                            v-for="(menu, index) in menus"
                            :key="index"
                            class="preview-chip"
                            :class="{ 'is-on': index === 0 }"
                            >{{ menu }}</span
                        >
                    </TransitionGroup>
                </div>
                <p class="flex items-center gap-1 text-xs font-medium">
                    <Plus class="size-3" />{{ copy.allergies }}
                </p>
                <TransitionGroup
                    tag="div"
                    name="preview"
                    class="flex flex-col gap-2 text-xs"
                >
                    <p v-if="shuttle" key="shuttle" class="preview-line">
                        {{ copy.shuttle }}
                        <span class="font-semibold tabular-nums">2</span>
                    </p>
                    <p v-if="stay" key="stay" class="preview-line">
                        {{ copy.stay }}
                    </p>
                    <p v-if="song" key="song" class="preview-line">
                        {{ copy.song }}
                    </p>
                </TransitionGroup>
                <span class="preview-send">{{ copy.send }}</span>
            </div>
        </WeddingThemeScope>
    </div>
</template>
