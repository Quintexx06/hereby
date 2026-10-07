<script setup lang="ts">
import { computed } from 'vue';
import InvitationPreview from '@/components/marketing/InvitationPreview.vue';
import { useAutoCycle } from '@/composables/motion/useAutoCycle';
import { previewHouseholds } from '@/content/invitation-preview';

defineProps<{
    label: string;
}>();

const { index, select, pause, resume } = useAutoCycle(previewHouseholds.length);
const household = computed(() => previewHouseholds[index.value]);
</script>

<!--
    The same wedding, re-addressed household by household and language by
    language. Pauses on hover/focus; a tap on a language takes over.
-->
<template>
    <figure
        class="flex flex-col items-center gap-4"
        @mouseenter="pause"
        @mouseleave="resume"
        @focusin="pause"
        @focusout="resume"
    >
        <div class="phone-frame">
            <Transition name="preview" mode="out-in">
                <InvitationPreview
                    :key="household.locale"
                    :household="household"
                />
            </Transition>
        </div>

        <figcaption class="sr-only">{{ label }}</figcaption>
        <div
            role="tablist"
            :aria-label="label"
            class="flex gap-1 rounded-full bg-background/90 p-1"
        >
            <button
                v-for="(item, itemIndex) in previewHouseholds"
                :key="item.locale"
                type="button"
                role="tab"
                class="locale-tab"
                :aria-selected="itemIndex === index"
                @click="select(itemIndex)"
            >
                {{ item.locale }}
            </button>
        </div>
    </figure>
</template>
