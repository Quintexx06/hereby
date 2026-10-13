<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Component } from 'vue';
import PreviewNewspaper from '@/components/locked/PreviewNewspaper.vue';
import PreviewPhotos from '@/components/locked/PreviewPhotos.vue';
import PreviewRows from '@/components/locked/PreviewRows.vue';
import PreviewSeating from '@/components/locked/PreviewSeating.vue';

export type LockedPreviewKind =
    | 'photos'
    | 'seating'
    | 'programme'
    | 'groupPhotos'
    | 'wayHome'
    | 'newspaper';

defineProps<{
    kind: LockedPreviewKind;
    /** Why it's locked and what it holds. */
    note: string;
    action: string;
    href: string;
}>();

const previews: Record<LockedPreviewKind, [Component, object]> = {
    photos: [PreviewPhotos, {}],
    seating: [PreviewSeating, {}],
    programme: [PreviewRows, { kind: 'programme' }],
    groupPhotos: [PreviewRows, { kind: 'groupPhotos' }],
    wayHome: [PreviewRows, { kind: 'wayHome' }],
    newspaper: [PreviewNewspaper, {}],
};
</script>

<!--
    A locked feature, shown as it would look with demo data, blurred behind
    a card that says what it is and where to unlock it. Hover sharpens the
    preview a little, as a tease; it never becomes readable.
-->
<template>
    <div class="locked-preview">
        <div class="locked-preview-stage" aria-hidden="true" inert>
            <component :is="previews[kind][0]" v-bind="previews[kind][1]" />
        </div>
        <div class="locked-preview-overlay">
            <div class="locked-preview-card">
                <p class="publish-note">{{ note }}</p>
                <Link :href="href" class="text-action hit-area">
                    {{ action }}
                </Link>
            </div>
        </div>
    </div>
</template>
