<script setup lang="ts">
import { computed } from 'vue';
import StepCouple from '@/components/setup/steps/StepCouple.vue';
import StepDate from '@/components/setup/steps/StepDate.vue';
import StepGuests from '@/components/setup/steps/StepGuests.vue';
import StepLook from '@/components/setup/steps/StepLook.vue';
import StepProgramme from '@/components/setup/steps/StepProgramme.vue';
import StepReview from '@/components/setup/steps/StepReview.vue';
import StepVenue from '@/components/setup/steps/StepVenue.vue';
import { steps as stepCopy } from '@/content/setup';
import type {
    Celebration,
    ProgrammeRow,
    SetupStepKey,
    ThemeSuggestion,
} from '@/types/setup';

/* One question per step; the body slides over when the step changes. */
const props = defineProps<{
    weddingId: number;
    step: SetupStepKey;
    presets: Record<Celebration, ProgrammeRow[]> | null;
    suggestion: ThemeSuggestion | null;
}>();

const components = {
    paar: StepCouple,
    datum: StepDate,
    ort: StepVenue,
    ablauf: StepProgramme,
    gaeste: StepGuests,
    look: StepLook,
    uebersicht: StepReview,
};

/* Each step gets only the props it declares. */
const stepProps = computed(() => {
    switch (props.step) {
        case 'ablauf':
            return { presets: props.presets };
        case 'look':
            return { suggestion: props.suggestion };
        case 'uebersicht':
            return { weddingId: props.weddingId };
        default:
            return {};
    }
});
</script>

<template>
    <Transition name="step" mode="out-in">
        <div :key="step" class="setup-body">
            <div>
                <h1 class="setup-question">{{ stepCopy[step].question }}</h1>
                <p class="setup-helper">{{ stepCopy[step].helper }}</p>
            </div>

            <component :is="components[step]" v-bind="stepProps" />
        </div>
    </Transition>
</template>
