<script setup lang="ts">
import { computed } from 'vue';
import BlockText from '@/components/invitation/blocks/BlockText.vue';
import type { InvitationBlock } from '@/types';

const props = defineProps<{ block: InvitationBlock }>();

/*
 * The line through the chapters: one gentle S-bend per chapter in a
 * 100-unit-wide box, stretched to the section's height. It draws itself as
 * the guest scrolls (CSS scroll-driven animation; static where unsupported).
 */
const path = computed(() => {
    const count = Math.max(props.block.items.length, 1);
    let d = 'M50 0';
    for (let i = 0; i < count; i++) {
        const top = i * 100;
        const bend = i % 2 === 0 ? 92 : 8;
        d += ` C${bend} ${top + 30} ${100 - bend} ${top + 70} 50 ${top + 100}`;
    }

    return { d, height: count * 100 };
});

const number = (index: number) => String(index + 1).padStart(2, '0');
</script>

<!-- "Unsere Geschichte" as a path: numbered chapters along a drawn line. -->
<template>
    <div class="flex flex-col gap-12">
        <div v-if="block.body" class="story-intro">
            <BlockText :text="block.body" />
        </div>

        <ol v-if="block.items.length" class="story-path">
            <svg
                class="story-line"
                :viewBox="`0 0 100 ${path.height}`"
                preserveAspectRatio="none"
                aria-hidden="true"
            >
                <path
                    class="story-line-ghost"
                    :d="path.d"
                    vector-effect="non-scaling-stroke"
                />
                <path
                    class="story-line-ink"
                    :d="path.d"
                    pathLength="1"
                    vector-effect="non-scaling-stroke"
                />
            </svg>
            <li
                v-for="(chapter, index) in block.items"
                :key="index"
                class="story-chapter"
                :data-side="index % 2 === 0 ? 'start' : 'end'"
            >
                <span class="story-node" aria-hidden="true" />
                <div class="story-card">
                    <span class="story-number" aria-hidden="true">{{
                        number(index)
                    }}</span>
                    <h3 class="story-title">{{ chapter.question }}</h3>
                    <p v-if="chapter.answer" class="story-text">
                        {{ chapter.answer }}
                    </p>
                </div>
            </li>
        </ol>
    </div>
</template>
