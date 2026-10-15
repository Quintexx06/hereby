<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import PageMasthead from '@/components/PageMasthead.vue';
import SaveBar from '@/components/SaveBar.vue';
import SetupStepBody from '@/components/setup/SetupStepBody.vue';
import { provideSetupForm } from '@/composables/useSetupForm';
import { dashboardPage } from '@/content/dashboard';
import { details as copy } from '@/content/details';
import { steps as stepCopy } from '@/content/setup';
import { pickStepFields } from '@/lib/setup';
import { dashboard } from '@/routes';
import { edit, update } from '@/routes/weddings/details';
import type { SetupForm, SetupStepKey, WeddingSetup } from '@/types/setup';

const props = defineProps<{
    wedding: WeddingSetup;
    step: Exclude<SetupStepKey, 'uebersicht'>;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: dashboardPage.title, href: dashboard() },
            { title: copy.title, href: '' },
        ],
    },
});

const tabs = ['paar', 'datum', 'ort', 'ablauf', 'gaeste', 'look'] as const;

const { id, couple_names: _names, ...fields } = props.wedding;
const form = useForm<SetupForm>({
    ...fields,
    venue_undecided: !fields.venue_name,
    // Weddings the team set up may have no celebration; the rows stay as they are.
    celebration: fields.celebration ?? 'day_and_evening',
});
provideSetupForm(form);

const note = computed(
    () =>
        ({ ablauf: copy.programmeNote, datum: copy.dateNote })[
            props.step as string
        ] ?? null,
);

function submit(): void {
    form.transform((data) => pickStepFields(props.step, data)).put(
        update.url([id, props.step]),
        { preserveScroll: true },
    );
}
</script>

<!-- The setup questions again, one tab at a time, for a live wedding. -->
<template>
    <Head :title="copy.title" />

    <div class="app-page gap-8">
        <PageMasthead :title="copy.title" :lede="copy.lede" />

        <nav :aria-label="copy.tabs" class="details-tabs">
            <Link
                v-for="tab in tabs"
                :key="tab"
                :href="edit([id, tab])"
                class="details-tab"
                :aria-current="tab === step ? 'page' : undefined"
                preserve-scroll
            >
                {{ stepCopy[tab].label }}
            </Link>
        </nav>

        <form class="details-form" novalidate @submit.prevent="submit">
            <p v-if="note" class="details-note">{{ note }}</p>

            <SetupStepBody
                :wedding-id="id"
                :step="step"
                :presets="null"
                :suggestion="null"
                embedded
            />

            <SaveBar
                class="details-savebar"
                :processing="form.processing"
                :dirty="form.isDirty"
                :saved="form.recentlySuccessful"
                :label="copy.save"
                :saved-label="copy.saved"
            />
        </form>
    </div>
</template>
