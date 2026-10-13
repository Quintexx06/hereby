<script setup lang="ts">
import LockedPreview from '@/components/locked/LockedPreview.vue';
import type { LockedPreviewKind } from '@/components/locked/LockedPreview.vue';
import { eveningPage as copy } from '@/content/evening';
import { show as publish } from '@/routes/weddings/publish';

defineProps<{ weddingId: number; preview: LockedPreviewKind }>();
</script>

<!-- Without Atelier: what the evening would be, blurred, and where to get it. -->
<template>
    <LockedPreview
        :kind="preview"
        :note="copy.locked"
        :action="copy.unlock"
        :href="publish(weddingId).url"
    />
</template>
