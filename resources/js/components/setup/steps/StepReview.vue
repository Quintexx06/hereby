<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    actions,
    celebrations,
    estimates,
    eventTypes,
    languages,
    review,
    themes,
} from '@/content/setup';
import { useSetupForm } from '@/composables/useSetupForm';
import { formatDate } from '@/lib/format';
import { show } from '@/routes/weddings/setup';
import type { SetupStepKey } from '@/types/setup';

const props = defineProps<{ weddingId: number }>();

const form = useSetupForm();

const rows = computed<{ label: string; value: string; step: SetupStepKey }[]>(
    () => [
        {
            label: review.couple,
            value: `${form.partner_one} & ${form.partner_two}`,
            step: 'paar',
        },
        {
            label: review.date,
            value: form.wedding_date
                ? formatDate(form.wedding_date, 'de-CH')
                : review.open,
            step: 'datum',
        },
        {
            label: review.deadline,
            value: form.rsvp_deadline
                ? formatDate(form.rsvp_deadline, 'de-CH')
                : review.open,
            step: 'datum',
        },
        {
            label: review.venue,
            value: form.venue_name
                ? [form.venue_name, form.venue_town].filter(Boolean).join(', ')
                : review.open,
            step: 'ort',
        },
        {
            label: review.programme,
            value: form.celebration
                ? `${celebrations[form.celebration].label}: ${form.events.map((event) => `${event.name || eventTypes[event.type]} ${event.time}`).join(', ')}`
                : review.open,
            step: 'ablauf',
        },
        {
            label: review.size,
            value: form.guest_estimate
                ? estimates[form.guest_estimate]
                : review.open,
            step: 'gaeste',
        },
        {
            label: review.languages,
            value: form.languages.map((locale) => languages[locale]).join(', '),
            step: 'gaeste',
        },
        { label: review.look, value: themes[form.theme].name, step: 'look' },
    ],
);

const href = (step: SetupStepKey) => show([props.weddingId, step]);
</script>

<template>
    <dl class="border-b">
        <div v-for="row in rows" :key="row.label" class="review-row">
            <dt class="text-muted-foreground">{{ row.label }}</dt>
            <dd class="font-medium">{{ row.value }}</dd>
            <Link :href="href(row.step)" class="link-underline text-sm">{{
                actions.change
            }}</Link>
        </div>
    </dl>
</template>
