<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import SetupFooter from '@/components/setup/SetupFooter.vue';
import SetupHeader from '@/components/setup/SetupHeader.vue';
import SetupPreview from '@/components/setup/SetupPreview.vue';
import SetupStepBody from '@/components/setup/SetupStepBody.vue';
import SetupSceneBand from '@/components/setup/SetupSceneBand.vue';
import SetupProgress from '@/components/setup/SetupProgress.vue';
import { steps as stepCopy } from '@/content/setup';
import { provideSetupForm } from '@/composables/useSetupForm';
import { pickStepFields } from '@/lib/setup';
import { complete, update } from '@/routes/weddings/setup';
import type {
    Celebration,
    ProgrammeRow,
    SetupForm,
    SetupStepKey,
    SetupStepState,
    ThemeSuggestion,
    WeddingSetup,
} from '@/types/setup';

const props = defineProps<{
    wedding: WeddingSetup;
    step: SetupStepKey;
    steps: SetupStepState[];
    presets: Record<Celebration, ProgrammeRow[]> | null;
    suggestion: ThemeSuggestion | null;
}>();

const { id, couple_names: _names, ...fields } = props.wedding;
const form = useForm<SetupForm>({
    ...fields,
    // Past the venue step without a venue means "noch offen" was chosen.
    venue_undecided:
        Boolean(
            props.steps.find((item) => item.value === 'ablauf')?.reachable,
        ) && !fields.venue_name,
    languages: fields.languages.length ? fields.languages : ['de_CH'],
    theme:
        props.suggestion && fields.theme === 'ivory'
            ? props.suggestion.theme
            : fields.theme,
});
provideSetupForm(form);

/*
 * Inertia keeps this page mounted from step to step, so the form carries
 * every answer along. When the look step brings a suggestion and the couple
 * hasn't picked a theme yet, start on the suggestion.
 */
watch(
    () => props.suggestion,
    (suggestion) => {
        if (
            suggestion &&
            props.wedding.theme === 'ivory' &&
            form.theme === 'ivory'
        ) {
            form.theme = suggestion.theme;
        }
    },
);

const position = computed(() =>
    props.steps.findIndex((item) => item.value === props.step),
);
const previous = computed(() => props.steps[position.value - 1]?.value);
const next = computed(() => props.steps[position.value + 1]?.value ?? null);

/* Drives the quiet "Gespeichert", so pausing never feels risky. */
const saved = ref(false);
let savedTimer: ReturnType<typeof setTimeout> | undefined;
const copy = computed(() => stepCopy[props.step]);
const isReview = computed(() => props.step === 'uebersicht');

function submit(): void {
    if (isReview.value) {
        form.transform(() => ({})).post(complete.url(id));

        return;
    }

    form.transform((data) => pickStepFields(props.step, data)).put(
        update.url([id, props.step]),
        {
            preserveScroll: true,
            onSuccess: () => {
                saved.value = true;
                clearTimeout(savedTimer);
                savedTimer = setTimeout(() => (saved.value = false), 2600);
            },
        },
    );
}
</script>

<template>
    <Head :title="`${copy.label} – Einrichten`" />

    <div class="setup-shell">
        <div class="setup-main">
            <SetupHeader />

            <SetupProgress :wedding-id="id" :current="step" :states="steps" />
            <SetupSceneBand :step="step" class="mt-6" />

            <form
                class="flex flex-1 flex-col"
                novalidate
                @submit.prevent="submit"
            >
                <SetupStepBody
                    :wedding-id="id"
                    :step="step"
                    :presets="presets"
                    :suggestion="suggestion"
                />

                <SetupFooter
                    :wedding-id="id"
                    :previous="previous"
                    :saved="saved"
                    :processing="form.processing"
                    :is-review="isReview"
                />
            </form>
        </div>

        <SetupPreview :step="step" :next="next" />
    </div>
</template>
