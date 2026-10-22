<script setup lang="ts">
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import type { InvitationBlock } from '@/types';

/* Numerals: how a design counts its chapters (I, II … or 01, 02 …). */
const { block, numerals = 'none' } = defineProps<{
    block: InvitationBlock;
    numerals?: 'roman' | 'figures' | 'none';
}>();

const ROMAN = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];
const mark = (index: number): string =>
    numerals === 'roman'
        ? (ROMAN[index] ?? String(index + 1))
        : String(index + 1).padStart(2, '0');
</script>

<!-- «Unsere Geschichte»: the couple's words, then their chapters in order. -->
<template>
    <div class="inv-story">
        <div v-if="block.body" class="inv-story-lede">
            <BlockText :text="block.body" />
        </div>
        <ol v-if="block.items.length" class="inv-story-list">
            <li
                v-for="(item, index) in block.items"
                :key="index"
                class="inv-story-item"
            >
                <span
                    v-if="numerals !== 'none'"
                    class="inv-story-mark"
                    aria-hidden="true"
                    >{{ mark(index) }}</span
                >
                <h3 class="inv-story-when">{{ item.question }}</h3>
                <p class="inv-story-what">{{ item.answer }}</p>
            </li>
        </ol>
    </div>
</template>
