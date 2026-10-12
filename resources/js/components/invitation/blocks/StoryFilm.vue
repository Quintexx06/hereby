<script setup lang="ts">
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import type { InvitationBlock } from '@/types';

defineProps<{ block: InvitationBlock }>();

const number = (index: number) => String(index + 1).padStart(2, '0');
</script>

<!-- Panorama: the story as frames of a film strip, swiped or scrolled sideways. -->
<template>
    <div class="flex flex-col gap-10">
        <div v-if="block.body" class="story-intro">
            <BlockText :text="block.body" />
        </div>
        <ol v-if="block.items.length" class="story-film" tabindex="0">
            <li
                v-for="(chapter, index) in block.items"
                :key="index"
                class="story-frame"
            >
                <span class="story-frame-number" aria-hidden="true">{{
                    number(index)
                }}</span>
                <h3 class="story-title">{{ chapter.question }}</h3>
                <p v-if="chapter.answer" class="story-text">
                    {{ chapter.answer }}
                </p>
            </li>
        </ol>
    </div>
</template>
