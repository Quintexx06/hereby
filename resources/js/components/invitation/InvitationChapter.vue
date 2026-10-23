<script setup lang="ts">
import type { Component } from 'vue';
import type { InvitationBlock } from '@/types';

/* Icon: a sign set before the heading, for designs that draw one. */
defineProps<{ block: InvitationBlock; heading: string; icon?: Component }>();
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
            <component
                :is="icon"
                v-if="icon"
                class="inv-chapter-icon"
                aria-hidden="true"
            />
            {{ heading }}
        </h2>
        <slot />
    </section>
</template>
