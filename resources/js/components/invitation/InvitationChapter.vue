<script setup lang="ts">
import type { InvitationBlock } from '@/types';

defineProps<{ block: InvitationBlock; heading: string }>();
</script>

<!-- One of the couple's chapters (their story, their questions) under its heading. -->
<template>
    <section
        :id="`block-${block.id}`"
        class="inv-chapter"
        :data-block="block.type"
        :aria-labelledby="`block-${block.id}-heading`"
    >
        <h2 :id="`block-${block.id}-heading`" class="inv-chapter-heading">
            {{ heading }}
        </h2>
        <slot />
    </section>
</template>
