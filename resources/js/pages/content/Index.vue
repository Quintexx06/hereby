<script setup lang="ts">
import PageMasthead from '@/components/PageMasthead.vue';
import { Head, router } from '@inertiajs/vue3';
import { Eye, Plus } from '@lucide/vue';
import { computed, ref } from 'vue';
import BlockEditor from '@/components/content/BlockEditor.vue';
import CouplePhotos from '@/components/content/CouplePhotos.vue';
import InvitationPreview from '@/components/content/InvitationPreview.vue';
import { blockTypes, contentPage as copy } from '@/content/content';
import { dashboardPage } from '@/content/dashboard';
import { dashboard } from '@/routes';
import { preview } from '@/routes/weddings';
import { reorder, store } from '@/routes/weddings/content';
import type {
    ContentBlockType,
    ContentEvent,
    ContentWedding,
    CouplePhoto,
    EditableBlock,
} from '@/types';

const props = defineProps<{
    wedding: ContentWedding;
    blocks: EditableBlock[];
    events: ContentEvent[];
    photos: CouplePhoto[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: copy.title, href: '' },
        ],
    },
});

const missing = computed(() =>
    (Object.keys(blockTypes) as ContentBlockType[]).filter(
        (type) => !props.blocks.some((block) => block.type === type),
    ),
);

/* Counts saves, so the preview beside the editor loads the new state. */
const saves = ref(0);

function add(type: ContentBlockType): void {
    router.post(
        store.url(props.wedding.id),
        { type },
        { preserveScroll: true },
    );
}

function move(index: number, direction: -1 | 1): void {
    const ids = props.blocks.map((block) => block.id);
    [ids[index], ids[index + direction]] = [ids[index + direction], ids[index]];
    router.put(
        reorder.url(props.wedding.id),
        { ids },
        { preserveScroll: true },
    );
}
</script>

<template>
    <Head :title="copy.title" />

    <div class="app-page gap-10">
        <PageMasthead :title="copy.title" :lede="copy.lede" scene="petals">
            <template #actions>
                <a
                    :href="preview.url(wedding.id)"
                    target="_blank"
                    rel="noopener"
                    class="text-action hit-area"
                >
                    <Eye class="size-4" /> {{ copy.preview }}
                </a>
            </template>
        </PageMasthead>

        <CouplePhotos
            :wedding-id="wedding.id"
            :photos="photos"
            @changed="saves++"
        />

        <p v-if="blocks.length === 0" class="text-muted-foreground">
            {{ copy.empty }}
        </p>

        <div class="content-layout">
            <div class="flex max-w-3xl flex-col gap-6">
                <BlockEditor
                    v-for="(block, index) in blocks"
                    :key="block.id"
                    :block="block"
                    :wedding="wedding"
                    :events="events"
                    :first="index === 0"
                    :last="index === blocks.length - 1"
                    @move="(direction) => move(index, direction)"
                    @saved="saves++"
                />
            </div>
            <InvitationPreview
                v-if="blocks.length || photos.length"
                :wedding="wedding"
                :version="saves + blocks.length"
            />
        </div>

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
    </div>
</template>
