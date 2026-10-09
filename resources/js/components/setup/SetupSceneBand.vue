<script setup lang="ts">
import { computed } from 'vue';
import { useSetupForm } from '@/composables/useSetupForm';
import { scenes, storyLine } from '@/lib/setupScenes';
import type { SetupStepKey } from '@/types/setup';

const props = defineProps<{ step: SetupStepKey }>();

const form = useSetupForm();
const story = computed(() => storyLine(props.step, form));
</script>

<!-- On phones the night panel becomes a photo band above the question. -->
<template>
    <div class="setup-band stage" aria-hidden="true">
        <Transition name="scene">
            <img
                :key="step"
                :src="scenes[step].src"
                alt=""
                class="setup-scene"
                decoding="async"
            />
        </Transition>
        <div class="setup-scene-scrim" />
        <p class="relative text-sm font-medium">{{ story }}</p>
    </div>
</template>
