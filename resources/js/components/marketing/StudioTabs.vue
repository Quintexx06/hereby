<script setup lang="ts">
import { ref } from 'vue';
import { studio } from '@/content/landing';

const props = defineProps<{
    templates: ReadonlyArray<{ slug: string; name: string; mood: string }>;
    active: number;
    /** The active tab's bar fills while the tour runs. */
    playing: boolean;
    showProgress: boolean;
}>();

const emit = defineEmits<{ select: [index: number] }>();

const tabs = ref<HTMLButtonElement[]>([]);

/** Arrow keys move between tabs, as in any tab list. */
const move = (direction: 1 | -1) => {
    const total = props.templates.length;
    const next = (props.active + direction + total) % total;
    emit('select', next);
    tabs.value[next]?.focus();
};
</script>

<!-- The template switcher: one large tab per template, its mood underneath. -->
<template>
    <div
        class="studio-tabs"
        role="tablist"
        :aria-label="studio.hint"
        @keydown.left.prevent="move(-1)"
        @keydown.right.prevent="move(1)"
    >
        <button
            v-for="(template, tabIndex) in templates"
            :key="template.slug"
            ref="tabs"
            type="button"
            role="tab"
            class="studio-tab"
            :aria-selected="tabIndex === active"
            :tabindex="tabIndex === active ? 0 : -1"
            @click="emit('select', tabIndex)"
        >
            <span class="studio-tab-name"
                >{{ template.name }}<span class="text-brand">.</span></span
            >
            <span class="studio-tab-mood">{{ template.mood }}</span>
            <span
                v-if="showProgress && tabIndex === active"
                :key="active"
                class="studio-tab-progress"
                :data-playing="playing"
                aria-hidden="true"
            />
        </button>
    </div>
</template>
