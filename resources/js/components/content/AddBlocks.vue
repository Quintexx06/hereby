<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { computed } from 'vue';
import { blockTypes, contentPage as copy } from '@/content/content';
import { store } from '@/routes/weddings/content';
import type { ContentBlockType, EditableBlock } from '@/types';

const props = defineProps<{ weddingId: number; blocks: EditableBlock[] }>();

const missing = computed(() =>
    (Object.keys(blockTypes) as ContentBlockType[]).filter(
        (type) => !props.blocks.some((block) => block.type === type),
    ),
);

function add(type: ContentBlockType): void {
    router.post(store.url(props.weddingId), { type }, { preserveScroll: true });
}
</script>

<!-- «Block hinzufügen»: the kinds of block the couple has not added yet. -->
<template>
    <section v-if="missing.length" class="flex flex-col gap-3">
        <h2 class="app-section-title">{{ copy.add }}</h2>
        <div class="chip-row">
            <button
                v-for="type in missing"
                :key="type"
                type="button"
                class="chip gap-2"
                @click="add(type)"
            >
                <Plus class="size-4" /> {{ blockTypes[type].label }}
            </button>
        </div>
    </section>
</template>
