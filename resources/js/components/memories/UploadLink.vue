<script setup lang="ts">
import CopyLink from '@/components/CopyLink.vue';
import { memoriesPage as copy } from '@/content/memories';

/* The one link the couple shares with every guest. */
defineProps<{ link: string }>();
</script>

<template>
    <CopyLink
        id="memories-link"
        :link="link"
        :label="copy.linkLabel"
        :hint="copy.linkHint"
    />
</template>
