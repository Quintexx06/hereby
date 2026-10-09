<script setup lang="ts">
import { Minus, Plus } from '@lucide/vue';
import { computed } from 'vue';
import { rsvpPreview as copy } from '@/content/rsvp';

const props = defineProps<{
    menus: string[];
    shuttle: boolean;
    stay: boolean;
    song: boolean;
}>();

/* Rough seconds per household: answering, then each extra question. */
const seconds = computed(
    () =>
        20 +
        (props.menus.length ? 10 : 0) +
        (props.shuttle ? 6 : 0) +
        (props.stay ? 5 : 0) +
        (props.song ? 12 : 0),
);
</script>

<!--
    The guest's reply as a plain sheet, without the couple's colours: it shows
    what is asked, not how it looks. Redrawn as the couple switches questions.
-->
<template>
    <div class="flex flex-col gap-4">
        <div class="flex items-baseline justify-between gap-4">
            <p class="text-sm font-medium">{{ copy.heading }}</p>
            <p class="text-sm text-muted-foreground tabular-nums">
                {{ copy.duration(seconds) }}
            </p>
        </div>
        <div class="rsvp-duration" :title="copy.limit">
            <span :style="{ width: `${Math.min(seconds / 60, 1) * 100}%` }" />
        </div>

        <div class="rsvp-sheet" aria-hidden="true">
            <p class="font-display text-2xl font-semibold tracking-tight">
                {{ copy.title }}
            </p>

            <div class="rsvp-sheet-block">
                <p class="text-sm font-medium">{{ copy.person }}</p>
                <div class="rsvp-segment">
                    <span class="is-on">{{ copy.attending }}</span>
                    <span>{{ copy.declined }}</span>
                </div>
                <Transition name="preview">
                    <div v-if="menus.length" class="flex flex-col gap-1.5">
                        <p class="text-xs text-muted-foreground">
                            {{ copy.menu }}
                        </p>
                        <span
                            v-for="(menu, index) in menus"
                            :key="index"
                            class="rsvp-option"
                            :class="{ 'is-on': index === 0 }"
                            >{{ menu }}</span
                        >
                    </div>
                </Transition>
                <p class="flex items-center gap-1 text-xs font-medium">
                    <Plus class="size-3" /> {{ copy.allergies }}
                </p>
            </div>

            <TransitionGroup tag="div" name="preview" class="flex flex-col">
                <div v-if="shuttle" key="shuttle" class="rsvp-sheet-row">
                    <span>{{ copy.shuttle }}</span>
                    <span class="rsvp-stepper">
                        <Minus class="size-3" /> 2 <Plus class="size-3" />
                    </span>
                </div>
                <div v-if="stay" key="stay" class="rsvp-sheet-row">
                    <span>{{ copy.stay }}</span>
                    <span class="rsvp-segment rsvp-segment-sm">
                        <span class="is-on">{{ copy.yes }}</span>
                        <span>{{ copy.no }}</span>
                    </span>
                </div>
                <div
                    v-if="song"
                    key="song"
                    class="rsvp-sheet-row flex-col items-stretch"
                >
                    <span>{{ copy.song }}</span>
                    <span class="rsvp-input">{{ copy.songPlaceholder }}</span>
                </div>
            </TransitionGroup>

            <span class="rsvp-send">{{ copy.send }}</span>
        </div>
    </div>
</template>
