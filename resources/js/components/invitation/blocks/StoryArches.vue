<script setup lang="ts">
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import type { InvitationBlock } from '@/types';

defineProps<{ block: InvitationBlock }>();

const numerals = [
    'I',
    'II',
    'III',
    'IV',
    'V',
    'VI',
    'VII',
    'VIII',
    'IX',
    'X',
    'XI',
    'XII',
];
</script>

<!-- Garten: each chapter of the story inside its own arch, like garden gates. -->
<template>
    <div class="flex flex-col gap-12">
        <div v-if="block.body" class="story-intro">
            <BlockText :text="block.body" />
        </div>
        <ol v-if="block.items.length" class="story-arches">
            <li
                v-for="(chapter, index) in block.items"
                :key="index"
                class="story-arch"
            >
                <span class="story-arch-numeral" aria-hidden="true">{{
                    numerals[index] ?? index + 1
                }}</span>
                <h3 class="story-title">{{ chapter.question }}</h3>
                <p v-if="chapter.answer" class="story-text">
                    {{ chapter.answer }}
                </p>
            </li>
        </ol>
    </div>
</template>
