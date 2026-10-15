<script setup lang="ts">
import { Plus, X } from '@lucide/vue';
import { bespoke } from '@/content/bespoke';

const colors = defineModel<string[]>({ required: true });
const copy = bespoke.form;

function set(index: number, event: Event): void {
    colors.value = colors.value.map((color, at) =>
        at === index ? (event.target as HTMLInputElement).value : color,
    );
}
</script>

<!-- Up to three colours: a round picker each, removable, one more to add. -->
<template>
    <div class="flex flex-wrap items-center gap-3">
        <span v-for="(color, index) in colors" :key="index" class="brief-color">
            <input
                type="color"
                :value="color"
                class="look-accent-input"
                :aria-label="`${copy.colors} ${index + 1}`"
                @input="set(index, $event)"
            />
            <span class="text-sm tabular-nums">{{ color }}</span>
            <button
                type="button"
                class="brief-color-remove"
                :aria-label="copy.removeColor"
                @click="colors = colors.filter((_, at) => at !== index)"
            >
                <X class="size-3.5" />
            </button>
        </span>
        <button
            v-if="colors.length < 3"
            type="button"
            class="text-action"
            @click="colors = [...colors, '#c8a2a8']"
        >
            <Plus class="size-4" /> {{ copy.addColor }}
        </button>
    </div>
</template>
