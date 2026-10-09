<script setup lang="ts">
import { computed, watch } from 'vue';
import WeddingThemeScope from '@/components/invitation/WeddingThemeScope.vue';
import InvitationPreview from '@/components/marketing/InvitationPreview.vue';
import { useSetupForm } from '@/composables/useSetupForm';
import { previewFromForm } from '@/lib/setup';
import { scenes, sceneSource, storyLine } from '@/lib/setupScenes';
import type { SetupStepKey } from '@/types/setup';

const props = defineProps<{
    step: SetupStepKey;
    next: SetupStepKey | null;
}>();

const form = useSetupForm();
const household = computed(() => previewFromForm(form));
const story = computed(() => storyLine(props.step, form));

/* Warm the next scene so the cross-fade never waits for the network. */
watch(
    () => props.next,
    (next) => {
        if (next) {
            new Image().src = sceneSource(scenes[next]);
        }
    },
    { immediate: true },
);
</script>

<!--
    The night side of the setup: a photograph per step, cross-fading as the
    couple moves on, with their invitation live on top and the story so far
    beneath it. This is the moment the setup is for.
-->
<template>
    <aside class="setup-preview stage" aria-label="Vorschau eurer Einladung">
        <Transition name="scene">
            <img
                :key="step"
                :src="sceneSource(scenes[step])"
                alt=""
                class="setup-scene"
                decoding="async"
            />
        </Transition>
        <div class="setup-scene-scrim" aria-hidden="true" />

        <div class="phone-frame relative">
            <WeddingThemeScope
                :theme="form.theme"
                lang="de-CH"
                :fill="false"
                class="overflow-hidden rounded-[2.1rem]"
            >
                <InvitationPreview :household="household" />
            </WeddingThemeScope>
        </div>

        <Transition name="story" mode="out-in">
            <p :key="step" class="setup-story" aria-live="polite">
                {{ story }}
            </p>
        </Transition>
    </aside>
</template>
