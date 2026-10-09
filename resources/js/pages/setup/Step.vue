<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import HerebyWordmark from '@/components/brand/HerebyWordmark.vue';
import SetupPreview from '@/components/setup/SetupPreview.vue';
import SetupProgress from '@/components/setup/SetupProgress.vue';
import StepCouple from '@/components/setup/steps/StepCouple.vue';
import StepDate from '@/components/setup/steps/StepDate.vue';
import StepGuests from '@/components/setup/steps/StepGuests.vue';
import StepLook from '@/components/setup/steps/StepLook.vue';
import StepProgramme from '@/components/setup/steps/StepProgramme.vue';
import StepReview from '@/components/setup/steps/StepReview.vue';
import StepVenue from '@/components/setup/steps/StepVenue.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { actions, steps as stepCopy } from '@/content/setup';
import { provideSetupForm } from '@/composables/useSetupForm';
import { pickStepFields } from '@/lib/setup';
import { dashboard } from '@/routes';
import { complete, show, update } from '@/routes/weddings/setup';
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

const components = {
    paar: StepCouple,
    datum: StepDate,
    ort: StepVenue,
    ablauf: StepProgramme,
    gaeste: StepGuests,
    look: StepLook,
    uebersicht: StepReview,
};

const position = computed(() =>
    props.steps.findIndex((item) => item.value === props.step),
);
const previous = computed(() => props.steps[position.value - 1]?.value);
const copy = computed(() => stepCopy[props.step]);
const isReview = computed(() => props.step === 'uebersicht');

/* Each step gets only the props it declares. */
const stepProps = computed(() => {
    switch (props.step) {
        case 'ablauf':
            return { presets: props.presets };
        case 'look':
            return { suggestion: props.suggestion };
        case 'uebersicht':
            return { weddingId: id };
        default:
            return {};
    }
});

function submit(): void {
    if (isReview.value) {
        form.transform(() => ({})).post(complete.url(id));

        return;
    }

    form.transform((data) => pickStepFields(props.step, data)).put(
        update.url([id, props.step]),
        {
            preserveScroll: true,
        },
    );
}
</script>

<template>
    <Head :title="`${copy.label} – Einrichten`" />

    <div class="setup-shell">
        <div class="setup-main">
            <header class="setup-header">
                <Link :href="dashboard()" aria-label="Zum Dashboard"
                    ><HerebyWordmark
                /></Link>
                <Link
                    :href="dashboard()"
                    class="link-underline text-sm text-muted-foreground"
                >
                    {{ actions.saveAndExit }}
                </Link>
            </header>

            <SetupProgress :wedding-id="id" :current="step" :states="steps" />

            <form
                class="flex flex-1 flex-col"
                novalidate
                @submit.prevent="submit"
            >
                <div class="setup-body">
                    <div>
                        <h1 class="setup-question">{{ copy.question }}</h1>
                        <p class="setup-helper">{{ copy.helper }}</p>
                    </div>

                    <component :is="components[step]" v-bind="stepProps" />
                </div>

                <div class="setup-footer">
                    <Button
                        v-if="previous"
                        variant="ghost"
                        size="pill"
                        as-child
                    >
                        <Link :href="show([id, previous])">{{
                            actions.back
                        }}</Link>
                    </Button>
                    <span v-else />
                    <Button
                        type="submit"
                        size="pill"
                        :disabled="form.processing"
                    >
                        <Spinner v-if="form.processing" />
                        {{ isReview ? actions.finish : actions.next }}
                    </Button>
                </div>
            </form>
        </div>

        <SetupPreview />
    </div>
</template>
